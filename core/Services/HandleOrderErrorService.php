<?php

namespace Core\Services;

use App\Jobs\ProcessOrder;
use Core\Functions\UpdateWallet\Facade\UpdateWalletFacade;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Traits\RedisWallet;
use Illuminate\Support\Facades\DB;

class HandleOrderErrorService
{
    use RedisWallet;

    private $orderRepository;
    private $walletGameRepository;
    private $updateWalletFacade;

    public function __construct(
        OrderInterface $orderRepository,
        WalletGameInterface $walletGameRepository,
        UpdateWalletFacade $updateWalletFacade
    ) {
        $this->orderRepository = $orderRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->updateWalletFacade = $updateWalletFacade;
    }

    public function handleOrderError()
    {
        $orders = $this->orderRepository->getOrderError();
        if (!empty($orders)) {
            foreach ($orders as $order) {
                try {
                    DB::beginTransaction();
                    $this->orderRepository->update(['is_handle' => 1], ['id' => $order->id]);

                    $note = strtr(config('constants.history_repay'), [
                        '{COIN}' => $order->coin,
                        '{AMOUNT}' => number_format($order->amount, 2),
                        '{DATE}' => $order->date
                    ]);

                    $history = [
                        'user_id' => $order->user_id,
                        'wallet' => $order->wallet,
                        'amount' => $order->amount,
                        'note' => $note,
                        'type' => config('constants.type_history.repay'),
                    ];
                    $this->updateWalletFacade->update($order->user_id, [$history]);

                    DB::commit();

                    $this->updateWallet($order->user_id, $order->wallet, $order->amount);
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->walletGameRepository->delRedis($order->user_id);
                }
            }
        }
    }
}
