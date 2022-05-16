<?php

namespace Core\Services;

use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Repositories\Eloquents\LevelRepository;
use Core\Repositories\Redis\RedisUserRepository;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpLevelService
{
    use RedisUser;

    private $walletGameRepository;
    private $historyWalletRepository;
    private $userRepository;
    private $levelRepository;
    private $orderRepository;

    public function __construct(
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository,
        RedisUserRepository $userRepository,
        LevelRepository $levelRepository,
        OrderInterface $orderRepository
    ) {
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->userRepository = $userRepository;
        $this->levelRepository = $levelRepository;
        $this->orderRepository = $orderRepository;
    }

    public function upgradeLevel()
    {
        $users = $this->userRepository->where([['level', '>', 0], ['verify', config('constants.verify_user')]]);
        if (empty($users)) {
            return;
        }

        foreach ($users as $user) {
            $totalTrade = $this->orderRepository->totalAmountFromMondayWeek([$user->id]);
            if ($totalTrade < 200) {
                continue;
            }

            $children = $user->childrenHasVerify;
            if (empty($children)) {
                continue;
            }

            $totalF1Trade200 = 0;
            foreach ($children as $child) {
                if ($child->total_order >= 200) {
                    $totalF1Trade200++;
                }
            }

            $levels = $this->levelRepository->whereAll([['name', '>', $user->level]]);

            if (!empty($levels)) {
                $arrChildrenId = [];
                $this->getArrChildrenId($children, $arrChildrenId);
                $totalOrderOfChild = $this->orderRepository->totalAmountFromMondayWeek($arrChildrenId);

                foreach ($levels as $levelUpgrade) {
                    if ($totalF1Trade200 < $levelUpgrade->total_f1 || $totalOrderOfChild < $levelUpgrade->total_trade) {
                        break;
                    }

                    $walletMain = $user->walletMain;
                    if (!empty($walletMain)) {
                        try {
                            DB::beginTransaction();
                            $this->userRepository->update(['level' => $levelUpgrade->name], $user);

                            // cal money and save history
                            $note = strtr(config('constants.history_commission_master_ib'),
                                ['{NAME}' => $levelUpgrade->name]);
                            $type = config('constants.type_history.commission_level');
                            $this->calMoneyAndSaveHistory($user->id, $walletMain['type'], $levelUpgrade->master_ib,
                                $note, $type);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                        }
                    }
                }
            }
        }
    }

    private function getArrChildrenId($children, &$results)
    {
        if (count($children) > 0) {
            foreach ($children as $child) {
                $results[] = $child->id;
                $this->getArrChildrenId($child->children, $results);
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
