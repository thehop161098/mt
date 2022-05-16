<?php

namespace Core\Functions\UpdateWallet\Services;

use Core\Repositories\Contracts\HistoryWalletInterface;

class SaveHistory
{

    private $historyWalletRepository;

    public function __construct(HistoryWalletInterface $historyWalletRepository)
    {
        $this->historyWalletRepository = $historyWalletRepository;
    }

    public function save($data)
    {
        $this->historyWalletRepository->create($data);
    }
}
