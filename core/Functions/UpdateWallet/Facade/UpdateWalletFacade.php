<?php

namespace Core\Functions\UpdateWallet\Facade;


use Core\Functions\UpdateWallet\Services\CalculatorWallet;
use Core\Functions\UpdateWallet\Services\SaveHistory;

class UpdateWalletFacade
{
    private $calculatorWallet;
    private $saveHistory;

    public function __construct(CalculatorWallet $calculatorWallet, SaveHistory $saveHistory)
    {
        $this->calculatorWallet = $calculatorWallet;
        $this->saveHistory = $saveHistory;
    }

    public function update(int $userId, array $history)
    {
        $this->saveHistory($history);
        $this->calculatorWallet->calculator($userId);
    }

    private function saveHistory(array $history)
    {
        foreach ($history as $his) {
            $this->saveHistory->save($his);
        }
    }
}
