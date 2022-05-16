<?php

namespace Core\Functions\UpdateWallet\Services;

use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\WalletGameInterface;

class CalculatorWallet
{

    private $historyWalletRepository;
    private $walletGameRepository;

    public function __construct(
        HistoryWalletInterface $historyWalletRepository,
        WalletGameInterface $walletGameRepository
    ) {
        $this->historyWalletRepository = $historyWalletRepository;
        $this->walletGameRepository = $walletGameRepository;
    }

    public function calculator($userId)
    {
        $history = $this->historyWalletRepository->summaryHistoryWallet($userId);
        if (!empty($history)) {
            $wallets = [
                config('constants.trial_wallet') => $history[config('constants.trial_wallet')],
                config('constants.discount_wallet') => $history[config('constants.discount_wallet')],
                config('constants.main_wallet') => $history[config('constants.main_wallet')]
            ];
            $this->walletGameRepository->updateAllWalletUser($userId, $wallets);
        }
    }
}
