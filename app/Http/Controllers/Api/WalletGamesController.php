<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class WalletGamesController extends Controller
{
    use RedisUser;

    private $redisWalletGameRepository;

    public function __construct(RedisWalletGameRepository $redisWalletGameRepository)
    {
        $this->redisWalletGameRepository = $redisWalletGameRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAmountWalletGame()
    {
        $user = RedisUser::getUser(Auth::user());
        $key = 'walletGameSelected:' . $user->id;
        $wallet = Redis::get($key);
        $amount = 0;
        if (!empty($wallet)) {
            $wallet = json_decode($wallet);
            $amount = $wallet->amount;
        }
        return response(['amount' => $amount]);
    }

    public function getWallets()
    {
        $user = RedisUser::getUser(Auth::user());
        $wallets = $this->redisWalletGameRepository->where($user->id);
        dd($wallets);
    }
}
