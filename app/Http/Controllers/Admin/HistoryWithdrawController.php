<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryWithdraw\UpdateHistoryWithdraw;
use App\Http\Requests\Admin\HistoryWithdraw\IndexHistoryWithdraw;
use App\Models\HistoryWithdraw;
use Brackets\AdminListing\Facades\AdminListing;
use Core\Repositories\Eloquents\HistoryWalletRepository;
use Core\Repositories\Eloquents\HistoryWithdrawRepository;
use Core\Repositories\Eloquents\WalletGameRepository;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Core\Repositories\Redis\RedisUserRepository;
use Core\Factories\NotificationFactory;

class HistoryWithdrawController extends Controller
{

    public $walletGameRepository;
    public $redisWalletGameRepository;
    public $historyWalletRepository;
    public $historyWithdrawRepository;
    public $userRepository;

    public function __construct(
        WalletGameRepository $walletGameRepository,
        RedisWalletGameRepository $redisWalletGameRepository,
        HistoryWalletRepository $historyWalletRepository,
        HistoryWithdrawRepository $historyWithdrawRepository,
        RedisUserRepository $userRepository
    ) {
        $this->walletGameRepository = $walletGameRepository;
        $this->redisWalletGameRepository = $redisWalletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->historyWithdrawRepository = $historyWithdrawRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexHistoryWithdraw $request
     * @return array|Factory|View
     */
    public function index(IndexHistoryWithdraw $request)
    {

        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'status', 'orderDirection' => 'asc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(HistoryWithdraw::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            [
                'id',
                'user_id',
                'coin',
                'amount_fee',
                'amount_convert',
                'amount',
                'code',
                'status',
                'reason',
                'created_at',
                'tx_hash',
                'wallet',
                'network'
            ],

            // set columns to searchIn
            ['id', 'coin', 'code', 'reason'],

            function ($query) use ($request) {
                $query->whereNotIn('status',
                    [config('constants.status_withdraw.temp'), config('constants.status_withdraw.expired')]);
                if ($request->has('status')) {
                    $query->where('status', $request->get('status'));
                }
                $query->with(['user']);
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

        return view('admin.history-withdraw.index', ['data' => $data]);
    }

    public function ajaxChangeStatusWithdraw(Request $request)
    {
        $id = $request->id ?? 0;
        $status = $request->status ?? "";
        $reason = $request->reason ?? "";

        $historyWithdraw = $this->historyWithdrawRepository->find($id);
        if (!empty($historyWithdraw)) {
            $rs = $this->historyWithdrawRepository->update([['id', $id]], ['status' => $status, 'reason' => $reason]);
            if ($rs) {
                // Send mail when approved
                if ($status == config('constants.status_withdraw.approved')) {
                    $infoUser = $this->userRepository->first([['id', $historyWithdraw->user_id]]);
                    $totalWithdraw = $historyWithdraw->amount - $historyWithdraw->amount_fee;

                    $dataMail = [
                        'email' => $infoUser->email,
                        'fullname' => $infoUser->full_name,
                        'amount' => number_format($totalWithdraw, 2),
                        'address' => $historyWithdraw->code,
                    ];
                    $notify = new NotificationFactory();
                    $notify->produceNotification(config('constants.email.withdraw_success'), $dataMail);
                } elseif ($status == config('constants.status_withdraw.reject')) {
                    if ($historyWithdraw->wallet === config('constants.main_wallet')) {
                        $totalWithdraw = $historyWithdraw->amount;
                    } else {
                        $totalWithdraw = $historyWithdraw->amount * 2;
                    }
                    $this->redisWalletGameRepository->update(
                        ['amount' => floatval($totalWithdraw)],
                        [['user_id', $historyWithdraw->user_id], ['type', $historyWithdraw->wallet]],
                        $historyWithdraw->user_id
                    );
                    $this->historyWalletRepository->create([
                        'user_id' => $historyWithdraw->user_id,
                        'wallet' => $historyWithdraw->wallet,
                        'amount' => floatval($totalWithdraw),
                        'note' => strtr(config('constants.history_withdraw.reject'), [
                            '{AMOUNT}' => floatval($historyWithdraw->amount),
                        ]),
                        'type' => config('constants.type_history.withdraw_reject'),
                    ]);
                    return response([
                        'message' => 'Reject success'
                    ]);
                }
                return response([
                    'message' => 'Approved success'
                ]);
            }
        }
        return response([
            'message' => 'Error! An error occurred. Please try again later'
        ], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HistoryWithdraw $historyWithdraw
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(HistoryWithdraw $historyWithdraw)
    {
        $this->authorize('admin.history-withdraw.edit', $historyWithdraw);


        return view('admin.history-withdraw.edit', [
            'historyWithdraw' => $historyWithdraw,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateHistoryWithdraw $request
     * @param HistoryWithdraw $historyWithdraw
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateHistoryWithdraw $request, HistoryWithdraw $historyWithdraw)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values HistoryWithdraw
        $historyWithdraw->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/history-withdraws'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/history-withdraws');
    }
}
