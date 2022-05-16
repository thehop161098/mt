<?php

namespace App\Http\Controllers\Admin;

use App\Events\AutoTransferEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Wallet\BulkDestroyWallet;
use App\Http\Requests\Admin\Wallet\DestroyWallet;
use App\Http\Requests\Admin\Wallet\IndexWallet;
use App\Http\Requests\Admin\Wallet\StoreWallet;
use App\Http\Requests\Admin\Wallet\UpdateWallet;
use App\Models\Wallet;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Events\GenerateWalletEvent;
use Core\Repositories\SettingRepository;

class WalletsController extends Controller
{

    private $settingRepository;

    public function __construct(
        SettingRepository $settingRepository
    ) {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexWallet $request
     * @return array|Factory|View
     */
    public function index(IndexWallet $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Wallet::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'code', 'private_key', 'amount', 'amount_bsc', 'user_id', 'coin'],

            // set columns to searchIn
            ['code', 'private_key', 'amount_bsc'],

            function ($query) use ($request) {
                $query->with(['user:id,email']);
                if ($request->has('user')) {
                    $query->whereIn('user_id', $request->get('user'));
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
//         dd($data->toArray());
        return view('admin.wallet.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.wallet.create');

        return view('admin.wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWallet $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreWallet $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['amount'] = 0;
        // Store the Wallet
        $wallet = Wallet::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/wallets'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/wallets');
    }

    /**
     * Display the specified resource.
     *
     * @param Wallet $wallet
     * @throws AuthorizationException
     * @return void
     */
    public function show(Wallet $wallet)
    {
        $this->authorize('admin.wallet.show', $wallet);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Wallet $wallet
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Wallet $wallet)
    {
        $this->authorize('admin.wallet.edit', $wallet);


        return view('admin.wallet.edit', [
            'wallet' => $wallet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWallet $request
     * @param Wallet $wallet
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateWallet $request, Wallet $wallet)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Wallet
        $wallet->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/wallets'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/wallets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyWallet $request
     * @param Wallet $wallet
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyWallet $request, Wallet $wallet)
    {
        if(! empty($wallet->user_id)) {
            $wallet->user_id = NULL;
            $wallet->save();
        } else {
            $wallet->delete();
        }

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }
    public function reset(Request $request, Wallet $wallet)
    {
        $wallet->amount_bsc = 0;
        $wallet->save();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }
    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyWallet $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyWallet $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Wallet::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function autoCreate(Request $request)
    {
        event(new GenerateWalletEvent(config('constants.walletCate.eth')));

        return response()->json([
            'success' => true,
            'message' => 'Request auto create wallet successfully'
        ]);
    }

    public function autoTransfer(Request $request, Wallet $wallet)
    {
        $disabledDeposit = $this->settingRepository->getParam('disabled_deposit');

        if ($disabledDeposit != config('constants.publish.yes')) {
            return response()->json([
                'success' => false,
                'message' => 'Please disabled deposit before transfer'
            ], 422);
        }

        $root_address = $this->settingRepository->getParam('root_address');
        event(new AutoTransferEvent($wallet->id, $root_address));

        return response()->json([
            'success' => true,
            'message' => 'Request auto transfer successfully'
        ]);
    }
}
