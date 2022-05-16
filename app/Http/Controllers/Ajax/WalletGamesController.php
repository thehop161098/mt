<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Traits\RedisUser;

class WalletGamesController extends Controller
{
    use RedisUser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAmountWalletGame()
    {
        $wallet = $this->getRedisWallet();
        return response(['amount' => $wallet['amount']]);
    }

    public function getWallets()
    {
        $wallets = $this->getRedisWallets();
        return response($wallets);
    }

    public function getWalletSelected()
    {
        $walletSelected = $this->getRedisWallet();
        return response($walletSelected);
    }

    public function changeWalletSelected($type)
    {
        $walletSelected = $this->saveRedisWallets($type);
        if (empty($walletSelected)) {
            return response(['success' => false], 421);
        }

        return response(['success' => true, 'walletSelected' => $walletSelected]);
    }
}
