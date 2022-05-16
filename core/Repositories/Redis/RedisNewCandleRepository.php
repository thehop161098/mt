<?php

namespace Core\Repositories\Redis;

use Core\Repositories\Contracts\NewCandleInterface;
use Illuminate\Support\Facades\Redis;

class RedisNewCandleRepository implements NewCandleInterface
{
    function update($coin, $newClose)
    {
        $key = 'newCandle:' . $coin;
        $newCandles = $this->where($coin);
        if (!empty($newCandles) && isset($newCandles[59])) {
            $newCandles[59]['close'] = $newClose;
            Redis::set($key, json_encode($newCandles), 'EX', 59);
        }
    }

    public function where($coin)
    {
        $key = 'newCandle:' . $coin;
        $candles = Redis::get($key);
        return $candles ? json_decode($candles, true) : [];
    }
}
