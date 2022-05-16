<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Redis;

trait CacheTraint
{

    public function saveCache($key, $value, $duration = 60 * 60 * 24)
    {
        return Redis::set($key, $value, 'EX', $duration);
    }

    public function getCache($key)
    {
        return Redis::get($key);
    }

    public function delCache($key)
    {
        $array = Redis::keys($key . '*');
        if (!empty($array)) {
            return Redis::del($array);
        }
        return true;
    }
}
