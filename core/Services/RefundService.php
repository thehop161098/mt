<?php

namespace Core\Services;

use Carbon\Carbon;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Eloquents\RefundRepository;
use Core\Repositories\Eloquents\SettingRefundRepository;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\DB;

class RefundService
{
    use RedisUser;

    private $refundRepository;
    private $orderRepository;
    private $walletGameRepository;
    private $historyWalletRepository;
    private $settingRefundRepository;

    public function __construct(
        RefundRepository $refundRepository,
        OrderInterface $orderRepository,
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository,
        SettingRefundRepository $settingRefundRepository
    ) {
        $this->refundRepository = $refundRepository;
        $this->orderRepository = $orderRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->settingRefundRepository = $settingRefundRepository;
    }

    public function registerRefundOrder()
    {
        $user = $this->getUser();
        $isRefund = !empty($user->refund) ? true : false;

        if ($isRefund) {
            return [
                'status' => 422,
                'message' => 'You are in refund period'
            ];
        }
        $resultCheckRefund = $this->getTotalRefund($user);
        $totalLose = $resultCheckRefund['amount'];

        if ($totalLose <= 0) {
            return [
                'status' => 422,
                'message' => 'You are not eligible for the refund'
            ];
        }
        $settingRefund = $this->settingRefundRepository
            ->where([['amount', '<=', $totalLose]])
            ->latest()
            ->first();

        if (empty($settingRefund)) {
            return [
                'status' => 422,
                'message' => 'Cannot refund'
            ];
        }

        $dataRefund = [
            'user_id' => $user->id,
            'day' => $settingRefund->day,
            'percent' => $settingRefund->percent,
            'amount' => $totalLose,
            'amount_refund' => 0,
            'date_expired' => Carbon::now()->addDays($settingRefund->day)->format('Y-m-d')
        ];
        if (isset($resultCheckRefund['deposit_id'])) {
            $dataRefund['deposit_id'] = $resultCheckRefund['deposit_id'];
        }

        if (isset($resultCheckRefund['transfer_id'])) {
            $dataRefund['transfer_id'] = $resultCheckRefund['transfer_id'];
        }

        $refund = $this->refundRepository->create($dataRefund);
        if ($refund) {
            return [
                'status' => 200,
                'day' => $refund->day,
                'percent' => $refund->percent . '%',
                'amount' => '$' . number_format($refund->amount, 2),
                'amount_refund' => '$' . $refund->amount_refund,
                'date_expired' => date('d-m-Y', strtotime($refund->date_expired)),
                'message' => 'You have successfully registered for the refund'
            ];
        }

        return [
            'status' => 422,
            'message' => 'Refund registration failed'
        ];
    }

    public function getTotalRefund($user)
    {
        $result = ['amount' => 0];

        $lastDeposit = $this->historyWalletRepository->where([
            ['user_id', $user->id],
            ['type', config('constants.type_history.cron_deposit')]
        ])->latest()->first();

        $lastTransfer = $this->historyWalletRepository->where([
            ['user_id', $user->id],
            ['amount', '>', 0],
            ['type', config('constants.type_history.internal_transfer')]
        ])->latest()->first();

        if (empty($lastDeposit) && empty($lastTransfer)) {
            return $result;
        }
        $amountDeposit = $amountTransfer = 0;
        $deposit_id = $transfer_id = 0;
        if (!empty($lastDeposit)) {
            $amountDeposit = $lastDeposit->amount;
            $deposit_id = $lastDeposit->id;
        }

        if (!empty($lastTransfer)) {
            $amountTransfer = $lastTransfer->amount;
            $transfer_id = $lastTransfer->id;
        }

        $oldRefundDeposit = $this->refundRepository->where([
            ['user_id', $user->id],
            ['deposit_id', $deposit_id]
        ])->count();
        $oldRefundTransfer = $this->refundRepository->where([
            ['user_id', $user->id],
            ['transfer_id', $transfer_id]
        ])->count();
        $amountTotal = 0;
        if ($oldRefundDeposit === 0 && $amountDeposit > 0) {
            $amountTotal += $amountDeposit;
            $result['deposit_id'] = $deposit_id;
        }
        if ($oldRefundTransfer === 0 && $amountTransfer > 0) {
            $amountTotal += $amountTransfer;
            $result['transfer_id'] = $transfer_id;
        }

        $totalLoseOrder = $this->orderRepository->getProfitLose($user->id);
        $totalRefunded = $this->refundRepository->getTotalRefundLose($user->id);
        $totalLoseOrder += $totalRefunded;
        $amountTotal -= $user->total_withdrawal + $user->total_transfer_to;
        if ($amountTotal < 0) {
            return $result;
        }

        if ($totalLoseOrder > 0) {
            return $result;
        }
        $totalLoseOrder *= -1;

        $amount80Percent = $amountTotal / 100 * 80;

        if ($totalLoseOrder < $amount80Percent) {
            return $result;
        }

        if ($amountTotal < $totalLoseOrder) {
            $result['amount'] = $amountTotal;
            return $result;
        }

        $result['amount'] = $totalLoseOrder;
        return $result;
    }

    public function refundOrderDaily()
    {
        $currentDate = date('Y-m-d');
        $refunds = $this->refundRepository->where([['date_expired', '>=', $currentDate]])->get();
        if (empty($refunds)) {
            return;
        }

        foreach ($refunds as $refund) {
            if ($refund->amount_refund >= $refund->amount) {
                continue;
            }

            $amount_refund = $refund->percent * $refund->amount / 100 / $refund->day;
            $refund->amount_refund += $amount_refund;

            try {
                DB::beginTransaction();

                $this->refundRepository->save($refund);
                $note = strtr(config('constants.history_refund'), [
                    '{AMOUNT}' => $amount_refund
                ]);
                $type = config('constants.type_history.refund');
                $this->calMoneyAndSaveHistory(
                    $refund->user_id, config('constants.main_wallet'), $amount_refund, $note, $type
                );

                DB::commit();
            } catch (\Exception $e) {

                DB::rollback();
            }
        }
    }

    private function calMoneyAndSaveHistory($userId, $wallet, $amount, $note, $type)
    {
        // update money wallet main
        $this->walletGameRepository->update(['amount' => $amount], [
            ['user_id', $userId],
            ['type', $wallet]
        ], $userId);

        // save history wallet
        $this->historyWalletRepository->create([
            'user_id' => $userId,
            'wallet' => $wallet,
            'amount' => $amount,
            'note' => $note,
            'type' => $type,
        ]);
    }
}
