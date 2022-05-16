<?php

namespace Core\Services;

use App\Jobs\ProcessOrder;
use Core\Functions\UpdateWallet\Facade\UpdateWalletFacade;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Traits\CacheTraint;
use Core\Traits\RedisWallet;
use Illuminate\Support\Facades\DB;

class OrderService
{
    use RedisWallet, CacheTraint;

    private $orderRepository;
    private $walletGameRepository;
    private $historyWalletRepository;
    private $updateWalletFacade;

    public function __construct(
        OrderInterface $orderRepository,
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository,
        UpdateWalletFacade $updateWalletFacade
    ) {
        $this->orderRepository = $orderRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->updateWalletFacade = $updateWalletFacade;
    }

    public function handleOrder($userId, $amount, $coin, $walletType, $type)
    {
        $orderTypes = config('constants.orders');
        if (in_array($type, $orderTypes) && is_numeric($amount) && $amount > 0) {
            $dataOrder = (Object)[
                'user_id' => $userId,
                'amount' => $amount,
                'coin' => $coin,
                'walletType' => $walletType,
                'type' => $type
            ];
            ProcessOrder::dispatch($this, $dataOrder)->onQueue('order');
            usleep(500000);
            return [
                'status' => 201,
                'message' => 'Processing an order request'
            ];
        }
        return [
            'status' => 400,
            'message' => 'Invalid amount'
        ];
    }

    public function createOrder($userId, $amount, $coin, $walletType, $type)
    {
        $wallet = $this->getWallet($userId, $walletType);
        $wallet['amount'] = floatval($wallet['amount']);
        $amount = floatval($amount);

        if ($amount < 1) {
            return [
                'status' => 422,
                'message' => 'Minimum $1',
                'user_id' => $userId
            ];
        }

        if (empty($wallet) || $wallet['amount'] < $amount) {
            return [
                'status' => 422,
                'message' => 'Wallet does not have enough money',
                'user_id' => $userId
            ];
        }
        $second = date('s');
        if ($second > 29) {
            return [
                'status' => 422,
                'message' => 'Timeout order',
                'user_id' => $userId
            ];
        }

        $data = [
            'user_id' => $userId,
            'wallet' => $walletType,
            'coin' => $coin,
            'type' => $type,
            'amount' => $amount,
            'date' => date('Y-m-d H:i')
        ];

        try {
            DB::beginTransaction();
            $this->orderRepository->create($data);

            $newWallet = $wallet;
            $newWallet['amount'] = $wallet['amount'] - $amount;

            $note = strtr(config('constants.history_order'), [
                '{COIN}' => $coin,
                '{AMOUNT}' => $amount,
                '{DATE}' => date('Y-m-d H:i')
            ]);

            $history = [
                'user_id' => $userId,
                'wallet' => $walletType,
                'amount' => $amount * (-1),
                'note' => $note,
                'type' => config('constants.type_history.order'),
            ];
            $this->updateWalletFacade->update($userId, [$history]);

            DB::commit();
            $totalAmountWeek = $this->getCache(config('constants.cache.order_in_week' . $userId));
            if ($totalAmountWeek !== null) {
                $this->saveCache(config('constants.cache.order_in_week') . $userId, $totalAmountWeek + $amount);
            }

            $totalAmount = $this->getCache(config('constants.cache.order_all' . $userId));
            if ($totalAmount !== null) {
                $this->saveCache(config('constants.cache.order_all') . $userId, $totalAmount + $amount);
            }

            $this->updateWallet($userId, $walletType, $amount * (-1));

            return [
                'status' => 201,
                'message' => 'Order successful',
                'user_id' => $userId,
                'order' => $data,
                'walletSelected' => $newWallet
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $this->walletGameRepository->delRedis($userId);
            return [
                'status' => 422,
                'message' => 'Cannot handle order',
                'user_id' => $userId
            ];
        }
    }
}
