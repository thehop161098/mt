<?php

namespace Core\Services;

use App\Events\OrderResultEvent;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Repositories\Eloquents\CandleRepository;
use Core\Traits\TradingTrait;
use Illuminate\Support\Facades\DB;

class OrderResultService
{
    use TradingTrait;

    private $orderRepository;
    private $candleRepository;
    private $walletGameRepository;
    private $historyWalletRepository;

    public function __construct(
        OrderInterface $orderRepository,
        CandleRepository $candleRepository,
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->candleRepository = $candleRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
    }

    public function calResult()
    {
        $date = date('Y-m-d H:i', time() - 60);
        $candles = $this->candleRepository->where([
            ['date', $date]
        ])->select(['id', 'coin', 'close', 'open', 'prize'])->get();
        if (!empty($candles)) {
            $users = [];
            $ordersUpdate = [];
            $arrOrders = [];
            foreach ($candles as $candle) {
                $candleResult = TradingTrait::getResult($candle->open, $candle->close);
                $orders = $this->orderRepository->where([
                    ['coin', $candle->coin],
                    ['date', $date],
                    ['candle_id', null]
                ])->select(['id', 'user_id', 'wallet', 'coin', 'type', 'amount'])->get();
                if (!empty($orders)) {
                    foreach ($orders as $order) {
                        if (!isset($arrOrders[$order->user_id][$order->coin])) {
                            $arrOrders[$order->user_id][$order->coin]['check'] = 0;
                        }

                        if (!isset($arrOrders[$order->user_id][$order->coin]['isYellow']) &&
                            $order->type === config('constants.orders.yellow')) {
                            $arrOrders[$order->user_id][$order->coin]['isYellow'] = true;
                        } else {
                            $arrOrders[$order->user_id][$order->coin]['isYellow'] = false;
                        }

                        if (!isset($users[$order->user_id][$order->coin][$order->wallet])) {
                            $users[$order->user_id][$order->coin][$order->wallet] = [
                                'win' => 0,
                                'lose' => 0,
                                'total' => 0
                            ];
                        }

                        $data = [
                            'candle_id' => $candle->id,
                            'open' => $candle->open,
                            'close' => $candle->close,
                            'type' => $order->type
                        ];
                        if ($order->type === $candleResult) {
                            $data['profit'] = $order->amount * 0.95 * $candle->prize;
                            $arrOrders[$order->user_id][$order->coin]['prize'] = $candle->prize;

                            $users[$order->user_id][$order->coin][$order->wallet]['win'] += $data['profit'] + $order->amount;
                        } else {
                            $data['profit'] = $order->amount * (-1);
                            $users[$order->user_id][$order->coin][$order->wallet]['lose'] += $order->amount;
                        }
                        $users[$order->user_id][$order->coin][$order->wallet]['total'] += $order->amount;

                        $ordersUpdate[$order->user_id][$order->id] = $data;
                    }
                }
            }

            if (!empty($users)) {
                foreach ($users as $userId => $walletsCoins) {
                    try {
                        DB::beginTransaction();
                        $walletSend = [];
                        $walletPlus = [];
                        foreach ($walletsCoins as $coin => $wallets) {
                            foreach ($wallets as $typeWallet => $rst) {
                                if ($rst['win'] > 0) {
                                    $win = $rst['win'];
                                    if (!isset($walletPlus[$typeWallet])) {
                                        $walletPlus[$typeWallet] = 0;
                                    }
                                    $walletPlus[$typeWallet] += $win;
                                    $note = strtr(config('constants.history_result_order'), [
                                        '{COIN}' => $coin,
                                        '{AMOUNT}' => $win
                                    ]);
                                    $type = config('constants.type_history.result_order');
                                    $this->historyWalletRepository->create([
                                        'user_id' => $userId,
                                        'wallet' => $typeWallet,
                                        'amount' => $win,
                                        'note' => $note,
                                        'type' => $type,
                                    ]);
                                }
                                if ($rst['win'] > 0 && $rst['lose'] > 0) {
                                    $profit = $rst['win'] - $rst['total'];
                                } elseif ($rst['win'] > 0) {
                                    $profit = $rst['win'];
                                } else {
                                    $profit = $rst['lose'] * (-1);
                                }
                                $walletSend[$coin][$typeWallet]['profit'] = $profit;
                                $walletSend[$coin][$typeWallet]['money'] = $rst['win'];
                            }
                        }

                        if (!empty($walletPlus)) {
                            // update money wallet main
                            foreach ($walletPlus as $typeWallet => $win) {
                                $this->walletGameRepository->update(['amount' => $win], [
                                    ['user_id', $userId],
                                    ['type', $typeWallet]
                                ], $userId);
                            }
                        }

                        $orders = $ordersUpdate[$userId];
                        $arrOrdersUser = $arrOrders[$userId];

                        foreach ($orders as $orderId => $order) {
                            $this->orderRepository->update($order, [['id', $orderId]]);
                        }

                        DB::commit();

                        broadcast(new OrderResultEvent($walletSend, $userId, $arrOrdersUser))->toOthers();
                    } catch (\Exception $e) {
                        DB::rollback();
                    }
                }
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
