<?php

namespace Core\Services;

use Core\Repositories\Contracts\WalletInterface;
use Core\Repositories\Contracts\WalletGameInterface;
use Core\Repositories\SettingRepository;
use Core\Repositories\Eloquents\HistoryWalletRepository;

class NewAccountService
{
    private $walletRepository;
    private $walletGame;
    private $settingRepository;
    public $historyWalletRepository;

    public function __construct(
        WalletInterface $walletRepository,
        WalletGameInterface $walletGame,
        SettingRepository $settingRepository,
        HistoryWalletRepository $historyWalletRepository
    ) {
        $this->walletRepository = $walletRepository;
        $this->walletGame = $walletGame;
        $this->settingRepository = $settingRepository;
        $this->historyWalletRepository = $historyWalletRepository;
    }

    public function initData($user)
    {
        $configCoin = $this->settingRepository->getParam('coins', true);
        if (!empty($configCoin)) {
            foreach ($configCoin as $coin) {
                $checkWallet = $this->walletRepository->first(['user_id' => $user->id, 'coin' => $coin]);
                if (empty($checkWallet)) {
                    $walletEmpty = $this->walletRepository->first(['user_id' => null, 'coin' => $coin]);
                    if (!empty($walletEmpty)) {
                        $walletEmpty->user_id = $user->id;
                        $walletEmpty->save();
                    }
                }
            }
        }

        if (count($user->walletGames) === 0) {
            $datas = [
                ['user_id' => $user->id, 'type' => config('constants.main_wallet'), 'amount' => 0],
                ['user_id' => $user->id, 'type' => config('constants.discount_wallet'), 'amount' => 0],
                [
                    'user_id' => $user->id,
                    'type' => config('constants.trial_wallet'),
                    'amount' => config('constants.amount_plus_trial')
                ],
            ];
            foreach ($datas as $data) {
                $this->walletGame->create($data);
            }
            $this->historyWalletRepository->create([
                'user_id' => $user->id,
                'wallet' => $datas[2]['type'],
                'amount' => $datas[2]['amount'],
                'note' => strtr(config('constants.history_plus_create_account_trial'), [
                    '{AMOUNT}' => $datas[2]['amount'],
                ]),
                'type' => config('constants.type_history.create_account_trial')
            ]);
        }
    }
}
