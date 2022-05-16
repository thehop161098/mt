<?php

namespace Core\Services;

use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Repositories\Eloquents\AutoBotRepository;
use Core\Repositories\Eloquents\HistoryBotRepository;
use Core\Repositories\Eloquents\LevelRepository;
use Core\Repositories\Redis\RedisUserRepository;
use Core\Traits\CacheTraint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    use CacheTraint;

    private $userRepository;
    private $orderRepository;
    private $levelRepository;
    private $walletGameRepository;
    private $historyWalletRepository;
    private $historyBotRepository;
    private $autoBotRepository;

    public function __construct(
        RedisUserRepository $userRepository,
        OrderInterface $orderRepository,
        LevelRepository $levelRepository,
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository,
        HistoryBotRepository $historyBotRepository,
        AutoBotRepository $autoBotRepository
    ) {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->levelRepository = $levelRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->historyBotRepository = $historyBotRepository;
        $this->autoBotRepository = $autoBotRepository;
    }

    public function calculatorCommission()
    {
        $this->delCache(config('constants.cache.order_in_week'));
        $this->delCache(config('constants.cache.order_all'));

        $users = $this->userRepository->where([
            ['level', '>', 0],
            ['verify', config('constants.verify_user')],
        ]);
        if (empty($users)) {
            return;
        }

        $levels = $this->levelRepository->all();
        if (empty($levels)) {
            return;
        }
        $levelMax = $levels[count($levels) - 1]->name;

        foreach ($users as $user) {
            $children = $user->children;
            if (count($children) === 0) {
                continue;
            }

            $totalTrade = $user->total_order;

            if ($totalTrade < 200) {
                continue;
            }

            $arrChildrenId = [];
            $this->getChildrenInTree($children, $arrChildrenId, $levelMax);

            $arrCommission = [];
            $percentRoseF1 = 0;
            foreach ($levels as $level) {
                if (!isset($arrChildrenId[$level->name])) {
                    break;
                }

                if ($user->level >= $level->name) {
                    $percentRoseF1 = $level->commission_f1 / 100;

                    $arrCommissionFromChild = [];
                    foreach ($arrChildrenId[$level->name]['id'] as $key => $childId) {
                        $totalOrderOfChild = $this->orderRepository->totalAmountYesterday([$childId]);
                        if ($totalOrderOfChild > 0) {
                            $amount = $totalOrderOfChild * $level->amount / 100;
                            $note = strtr(config('constants.history_commission_from_child'), [
                                '{AMOUNT}' => $amount,
                                '{User}' => $arrChildrenId[$level->name]['name'][$key]
                            ]);
                            $arrCommissionFromChild[] = [
                                'note' => $note,
                                'amount' => $amount
                            ];
                        }
                    }

                    if (!empty($arrCommissionFromChild)) {
                        $arrCommission[$level->name] = $arrCommissionFromChild;
                    }
                }
            }

            try {
                DB::beginTransaction();

                if (!empty($arrCommission)) {
                    // cal commission for parent
                    $parent = $user->parent;
                    if (!empty($parent) && isset($arrCommission[1])) {
                        $totalComm = 0;
                        foreach ($arrCommission[1] as $comm) {
                            $totalComm += $comm['amount'];
                        }
                        $amount = $percentRoseF1 * $totalComm;

                        // cal money and save history
                        $walletMainParent = $parent->walletMain;
                        if (!empty($walletMainParent)) {
                            $note = strtr(config('constants.history_commission_from_child'), [
                                '{AMOUNT}' => $amount,
                                '{User}' => $user->full_name
                            ]);
                            $type = config('constants.type_history.commission_from_child');
                            $this->calMoneyAndSaveHistory(
                                $parent->id, $walletMainParent['type'], $amount, $note, $type
                            );
                        }
                    }

                    // cal commission daily for user
                    $walletMain = $user->walletMain;
                    if (!empty($walletMain)) {
                        foreach ($arrCommission as $level => $commission) {
                            foreach ($commission as $comm) {
                                $type = config('constants.type_history.commission_daily');
                                $this->calMoneyAndSaveHistory(
                                    $user->id, $walletMain['type'], $comm['amount'], $comm['note'], $type
                                );
                            }
                        }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
    }

    private function getChildrenInTree($children, &$results, $numMaxInTree, $numInTree = 1)
    {
        if ($numInTree <= $numMaxInTree) {
            if (count($children) > 0) {
                $arrChildrenId = [];
                $arrChildrenName = [];
                foreach ($children as $child) {
                    $arrChildrenId[] = $child->id;
                    $arrChildrenName[] = $child->full_name;
                    $numInTree++;
                    $this->getChildrenInTree($child->children, $results, $numMaxInTree, $numInTree);
                    $numInTree--;
                }
                if (!isset($results[$numInTree])) {
                    $results[$numInTree] = [
                        'id' => $arrChildrenId,
                        'name' => $arrChildrenName,
                    ];
                } else {
                    $results[$numInTree]['id'] = array_merge($results[$numInTree]['id'], $arrChildrenId);
                    $results[$numInTree]['name'] = array_merge($results[$numInTree]['name'], $arrChildrenName);
                }
            }
        }
    }

    private function calMoneyAndSaveHistory($userId, $wallet, $amount, $note, $type)
    {
        $fromDate = Carbon::now()->format('Y-m-d 00:00:00');
        $toDate = Carbon::now()->format('Y-m-d 23:59:59');
        $isSaveMoney = $this->historyWalletRepository->isInsertHistoryToDay([
            ['user_id', $userId],
            ['wallet', $wallet],
            ['type', $type],
            ['note', $note],
        ], $fromDate, $toDate);

        if ($isSaveMoney) {
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

    public function calculatorCommissionBot()
    {
        if (Carbon::now()->dayOfWeek == Carbon::SATURDAY || Carbon::now()->dayOfWeek == Carbon::SUNDAY) {
            return;
        }

        $users = $this->userRepository->all();
        if (empty($users)) {
            return;
        }

        foreach ($users as $user) {
            try {
                DB::beginTransaction();

                $walletMain = $user->walletMain;
                if (!empty($walletMain)) {
                    $currentBot = $this->historyBotRepository->currentBot($user->id);
                    if ($currentBot) {
                        $autoBot = $this->autoBotRepository->find($currentBot->bot_id);
                        if (!$autoBot) {
                            continue;
                        }

                        $commission = 0;
                        switch ($currentBot->time) {
                            case 7:
                                $commission = $autoBot->commission_7;
                                break;
                            case 21:
                                $commission = $autoBot->commission_21;
                                break;
                            case 30:
                                $commission = $autoBot->commission_30;
                                break;
                            case 90:
                                $commission = $autoBot->commission_90;
                                break;
                            default:
                                break;
                        }
                        if ($commission == 0) {
                            continue;
                        }

                        // $amount = $currentBot->price * $commission / $currentBot->time;
                        $amount = 0;
                        // $note = strtr(config('constants.history_commission_bot_daily'), [
                        //     '{AMOUNT}' => $amount
                        // ]);
                        $note = "The transaction has ended, the bot has a problem during the transaction";

                        $type = config('constants.type_history.commission_bot_daily');
                        $this->calMoneyAndSaveHistory(
                            $user->id, $walletMain['type'], $amount, $note, $type
                        );

                        // if ($user->parent) {
                        //     $walletMainParent = $user->parent->walletMain;
                        //     if (!empty($walletMainParent)) {
                        //         $amount = $amount * 10 / 100;
                        //         $note = strtr(config('constants.history_commission_bot_daily_parent'), [
                        //             '{AMOUNT}' => $amount,
                        //             '{USER}' => $user->full_name ? $user->full_name : $user->email
                        //         ]);
                        //         $type = config('constants.type_history.commission_bot_daily_parent');
                        //         $this->calMoneyAndSaveHistory(
                        //             $user->parent->id, $walletMainParent['type'], $amount, $note, $type
                        //         );
                        //     }
                        // }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
}
