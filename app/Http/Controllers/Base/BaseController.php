<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    use RedisUser;

    public function __construct(RedisWalletGameRepository $redisWalletGameRepository)
    {
        $wallets = $redisWalletGameRepository->where(1);
        $key = 'walletGameSelected:1';
        $walletSelected = Redis::get($key);
        if (!empty($walletSelected)) {
            $walletSelected = json_decode($walletSelected, true);
        }
        View::share(['wallets' => $wallets, 'walletSelected' => $walletSelected]);
    }
   
}
