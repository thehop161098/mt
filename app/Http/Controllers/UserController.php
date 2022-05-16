<?php

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\User\ChangePasswordRequest;
use App\Http\Requests\Frontend\User\GG2FARequest;
use App\Http\Requests\UpdateProfileRequest;
use Core\Factories\NotificationFactory;
use Core\Repositories\Contracts\UserInterface;
use Core\Repositories\Eloquents\PhoneCountryRepository;
use Core\Services\UserService;
use Core\Traits\FileTrait;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use RedisUser, FileTrait;

    protected $userService;
    private $userRepository;
    private $phoneCountryRepository;

    public function __construct(
        UserService $userService,
        UserInterface $userRepository,
        PhoneCountryRepository $phoneCountryRepository
    ) {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->phoneCountryRepository = $phoneCountryRepository;
    }

    public function edit()
    {
        $user = Auth::user();
        $phoneCountries = $this->phoneCountryRepository->all();
        return view('frontend.profile.edit', compact('user', 'phoneCountries'));
    }


    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->only($user->getFillable());
        $data['avatar'] = $this->uploadFile($request, 'avatar', config('constants.path_user'), $user->avatar);
        if ($this->userRepository->update($data, $user)) {
            toastr()->success('Profile has been saved successfully!');
        }
        return redirect()->route('user.edit');
    }

    public function identityCard()
    {
        $user = Auth::user();
        return view('frontend.profile.kyc', compact('user'));
    }

    public function postIdentityCard(Request $request)
    {
        if (auth()->user()->verify !== config('constants.not_verify_user')) {
            toastr()->warning('You are in the validation process!');
            return redirect()->route('identityCard');
        }
        
        $this->validator($request->all())->validate();
        $files = [$request->except('_token')];
        $user = Auth::user();
        $oldFiles = [$user->identity_card_after, $user->identity_card_before, $user->portrait];
        $data = $this->uploadIdentityCard($files, config('constants.path_user'), $oldFiles);
        $data['verify'] = config('constants.pending_verify_user');
        if ($this->userRepository->update($data, $user)) {
            toastr()->success('Upload identity card successfully!');
        }

        $notify = new NotificationFactory();
        $notify->produceNotification(config('constants.telegram.kyc_request'), [
            '{USER}' => auth()->user()->full_name
        ]);

        return redirect()->route('identityCard');
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'identity_card_after' => 'required|file|max:5120|mimes:jpg,png,jpeg',
            'identity_card_before' => 'required|file|max:5120|mimes:jpg,png,jpeg',
            'portrait' => 'required|file|max:5120|mimes:jpg,png,jpeg',
        ]);

        $validator->setAttributeNames([
            'portrait' => 'Selfie Image',
            'identity_card_before' => 'Front Side Image',
            'identity_card_after' => 'Back Side Image',
        ]); 

        // $validator->after(function ($validator) {
        //     dd($validator->fails());
        //     if ($validator->fails()) {
        //         dd($validator->errors());
        //     }
        // });

        return $validator;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $data = ['password' => Hash::make($request->new_password)];
        $this->userRepository->update($data, $user);
        return response()->json([
            'success' => 200
        ]);
    }

    public function generate2faSecret()
    {
        $data = $this->userService->generate2faSecret();
        $html = view('frontend.profile.2fa_settings', compact('data'))->render();
        return response()->json([
            'data' => $html
        ], 200);
    }

    public function toggle2fa(GG2FARequest $request)
    {
        $data = $this->userService->toggle2fa($request->all());
        if ($data['success'] === true && $data['status'] == 1) {
            return response()->json([
                'success' => $data['success'],
                'status' => $data['status'],
                'message' => 'Authenticator register successfull!'
            ], 200);
        } elseif ($data['success'] === true && $data['status'] == 0) {
            return response()->json([
                'success' => $data['success'],
                'status' => $data['status'],
                'message' => 'Authenticator disable successfull!'
            ], 200);
        } else {
            return response()->json([
                'errors' => ['secret' => ['6-digit code invalid!']],
            ], 422);
        }
    }

    public function verify2fa()
    {
        return redirect()->route('home');
    }
}
