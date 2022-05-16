<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\BulkDestroyUser;
use App\Http\Requests\Admin\User\DestroyUser;
use App\Http\Requests\Admin\User\IndexUser;
use App\Models\User;
use Brackets\AdminListing\Facades\AdminListing;
use Core\Repositories\Contracts\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    private $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(IndexUser $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(User::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            [
                'id',
                'full_name',
                'phone',
                'email',
                'email_verified_at',
                'avatar',
                'referral_code',
                'identity_card_after',
                'identity_card_before',
                'portrait',
                'verify',
                'level'
            ],

            // set columns to searchIn
            ['id', 'full_name', 'email', 'avatar', 'intro', 'referral_code'],

            function ($query) use ($request) {
                $query->with(['wallets', 'walletMain']);
                if ($request->has('wallets')) {
                    $query->whereIn('id', $request->get('wallets'));
                }
                if ($request->has('verify')) {
                    $query->where('verify', $request->get('verify'));
                }
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.user.index', ['data' => $data]);
    }

    public function destroy(DestroyUser $request, User $user)
    {
        if ($user->wallets->count()) {
            return response(['message' => trans('You can not delete')], 403);
        }

        $user->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyUser $request
     * @return Response|bool
     * @throws Exception
     */
    public function bulkDestroy(BulkDestroyUser $request): Response
    {
        $ids = $request->data['ids'];
        $users = User::find($ids);
        $arrId = [];
        foreach ($users as $user) {
            if (!$user->wallets->count()) {
                array_push($arrId, $user->id);
            }
        }
        DB::transaction(static function () use ($arrId) {
            collect($arrId)
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    User::whereIn('id', $bulkChunk)->delete();
                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function reject(Request $request, $id)
    {
        $user = User::find($id);
        // oldFile
        $oldFiles = [$user->identity_card_after, $user->identity_card_before, $user->portrait];
        $pathUpload = config('constants.path_user') . 'user' . $user->id . '/';
        foreach ($oldFiles as $oldFile) {
            $this->removeFile(public_path($pathUpload) . $oldFile);
        }
        $data = [
            'identity_card_after' => NULL,
            'identity_card_before' => NULL,
            'portrait' => NULL,
            'verify' => config('constants.not_verify_user'),
        ];
        if ($this->userRepository->update($data, $user)) {
            return response([
                'message' => 'Reject identity card successfully!'
            ]);
        }
        return response([
            'message' => 'Reject identity card failed!'
        ], 422);
    }

    public function removeFile($pathImg)
    {
        if (is_file($pathImg)) {
            unlink($pathImg);
        }
    }

    public function approve(Request $request, $id)
    {
        $user = User::find($id);
        $data = [
            'verify' => config('constants.verify_user'),
        ];
        if ($this->userRepository->update($data, $user)) {
            return response([
                'message' => 'Approve identity card successfully!'
            ]);
        }
        return response([
            'message' => 'Approve identity card failed!'
        ], 422);
    }
}
