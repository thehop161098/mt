<?php

namespace Core\Repositories\Redis;

use App\Models\Coin;
use Core\Repositories\Contracts\CoinInterface;
use Illuminate\Support\Facades\Redis;

class RedisCoinRepository implements CoinInterface
{
    public function getCoinSelected()
    {
        $coins = $this->find();
        return $coins[0] ?? null;
    }

    public function find()
    {
        $key = 'coins';
        // Redis::del(Redis::keys('coins'));
        $coins = Redis::get($key);
        if ($coins === null) {
            $coins = Coin::select('name', 'alias', 'image', 'is_gold')->where('publish', true)->orderBy('id',
                'asc')->get();
            if (!empty($coins)) {
                Redis::set($key, json_encode($coins), 'EX', 60 * 60 * 24);
            }
        } else {
            $coins = json_decode($coins);
        }
        return $coins;
    }

    public function update()
    {
        $key = 'coins';
        $coins = Coin::select('name', 'alias', 'image', 'is_gold')->where('publish', true)->orderBy('id', 'asc')->get();
        if (!empty($coins)) {
            Redis::set($key, json_encode($coins), 'EX', 60 * 60 * 24);
        }
    }
}
