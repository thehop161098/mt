<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Core\Functions\RegisterUser\Services\GenerateCodeService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Core\Factories\NotificationFactory;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $generateCode;

    /**
     * Create a new controller instance.
     * @param \Core\Functions\RegisterUser\Services\GenerateCodeService $generateCode
     * @return void
     */
    public function __construct(GenerateCodeService $generateCode)
    {
        $this->middleware('guest');
        $this->generateCode = $generateCode;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user)
            ?: redirect('/login')->with('message', 'You have successfully registered, please verify your email!');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'fullname' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['required'],
        ]);

        $referral_code = Arr::get($data, 'referral_code', null);
        if (!empty($referral_code)) {
            $validator->after(function ($validator) use ($referral_code) {
                $userReferral = User::where('code', $referral_code)->first();
                if (!$userReferral) {
                    $validator->errors()->add('referral_code', 'Referral code does not exists');
                }
            });
        }
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // create User
        $user = User::create([
            'full_name' => $data['fullname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'code' => $this->generateCode->generate(),
            'level' => 0,
            'verify' => config('constants.not_verify_user'),
            'referral_code' => $data['referral_code'] ?? null
        ]);

        $notify = new NotificationFactory();
        $notify->produceNotification(config('constants.email.verify_account'), $user);
        return $user;
    }
}
