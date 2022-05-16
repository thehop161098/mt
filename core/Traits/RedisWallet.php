<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisWallet
{
    private $key = 'walletGames:';

    public function updateWallet($userId, $type, $amount)
    {
        $wallets = Redis::get($this->key . $userId);
        if (true || !empty($wallets)) {
            $wallets = json_decode($wallets, true);
            foreach ($wallets as $key => $wallet) {
                if ($wallet['type'] === $type) {
                    $wallets[$key]['amount'] += $amount;
                    break;
                }
            }
            Redis::set($this->key . $userId, json_encode($wallets));
        }
    }

    public function getWallet($userId, $type)
    {
        $wallets = Redis::get($this->key . $userId);

        if (!empty($wallets)) {
            $wallets = json_decode($wallets, true);
            foreach ($wallets as $wallet) {
                if ($wallet['type'] === $type) {
                    return $wallet;
                }
            }
        }
        return null;
    }
}
