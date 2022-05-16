<?php

namespace Core\Services;

use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Repositories\Eloquents\LevelRepository;
use Core\Repositories\Redis\RedisUserRepository;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgencyService
{
    use RedisUser;

    private $walletGameRepository;
    private $historyWalletRepository;
    private $userRepository;
    private $levelRepository;

    public function __construct(
        WalletGameInterface $walletGameRepository,
        HistoryWalletInterface $historyWalletRepository,
        RedisUserRepository $userRepository,
        LevelRepository $levelRepository
    ) {
        $this->walletGameRepository = $walletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->userRepository = $userRepository;
        $this->levelRepository = $levelRepository;
    }

    public function buyAgency()
    {
        $user = Auth::user();
        if ($user->level) {
            return [
                'success' => false,
                'message' => 'You have already bought!'
            ];
        }

        $amount = 5;
        $walletMain = $this->getRedisWallet(config('constants.main_wallet'));
        if (empty($walletMain) || $walletMain['amount'] < $amount) {
            return [
                'success' => false,
                'message' => 'You dont have enough money!'
            ];
        }

        try {
            DB::beginTransaction();

            // set level for user
            $this->userRepository->update(['level' => 1], $user);

            // cal money and save history
            $note = config('constants.history_agency');
            $type = config('constants.type_history.commission_agency');
            $this->calMoneyAndSaveHistory($user->id, $walletMain['type'], $amount * (-1), $note, $type);

            // plus commission for parents
            $this->calCommissionAgencyForParents($user, $user->parent);

            // check upgrade level for parent
//            $this->upgradeLevelForParent($user->parent);

            DB::commit();
            return [
                'success' => true,
                'code' => $user->code,
                'referralCode' => $user->referral_url,
                'level' => $user->level,
                'message' => 'Buy successful'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Cannot buy agency'
            ];
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

    private function calCommissionAgencyForParents($user, $parent, $numInTree = 1)
    {
        if ($parent && $numInTree < 7) {
            $amount = $numInTree === 1 ? 50 : 5;

            $walletMain = $parent->walletMain;
            if (!empty($walletMain)) {
                // cal money and save history
                $note = strtr(config('constants.history_commission_agency'), [
                    '{AMOUNT}' => $amount,
                    '{USER}' => $user->full_name
                ]);
                $type = config('constants.type_history.commission_agency_parent');
                $this->calMoneyAndSaveHistory($parent->id, $walletMain['type'], $amount, $note, $type);
            }
            $this->calCommissionAgencyForParents($user, $parent->parent, $numInTree + 1);
        }
    }

    public function upgradeLevelForParent($user)
    {
        if (empty($user) || $user->level === 0) {
            return;
        }

        $levelUpgrade = $this->levelRepository->where([['name', '>', $user->level]]);
        $totalF1 = count($user->childrenHasAgency) + 1;

        if ($levelUpgrade && $totalF1 === $levelUpgrade->total_f1) {
            $walletMain = $user->walletMain;
            if (!empty($walletMain)) {
                $this->userRepository->update(['level' => $levelUpgrade->name], $user);

                // cal money and save history
                $note = strtr(config('constants.history_commission_master_ib'), ['{NAME}' => $levelUpgrade->name]);
                $type = config('constants.type_history.commission_level');
                $this->calMoneyAndSaveHistory($user->id, $walletMain['type'], $levelUpgrade->master_ib, $note, $type);
            }
        }
    }
}
