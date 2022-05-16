<?php

namespace Core\Repositories\Redis;

use App\Models\Candle;
use Core\Repositories\Contracts\CandleInterface;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class RedisCandleRepository implements CandleInterface
{
    private $candles;

    public function __construct(Candle $candles)
    {
        $this->candles = $candles;
    }

    public function find($coin, $limit)
    {
        $key = 'candles:latest:' . $coin;
//        Redis::del(Redis::keys('candles:latest:*'));
        $candles = Redis::get($key);
        if (empty($candles)) {
            $candles = Candle::select('round', 'open', 'close', 'high', 'low', 'date')
                ->where([['coin', $coin], ['date', '<>', date('Y-m-d H:i')]])
                ->take($limit)
                ->orderBy('id', 'desc')
                ->get();
            Redis::set($key, json_encode($candles));
        } else {
            $candles = json_decode($candles, true);
        }
        return $candles;
    }

    public function findCircleHistory($coin, $limit)
    {
        $key = 'circleCandles:latest:' . $coin;
        $circleHistory = Redis::get($key);
        return $circleHistory ? json_decode($circleHistory, true) : [];
    }

    public function findTime($time)
    {
        return $this->candles->where('date', $time)->orderBy('coin', 'asc')->get();
    }

    public function first($id)
    {
        return $this->candles->find($id);
    }

    public function update($id, $updates)
    {
        return $this->candles->where('id', $id)->update($updates);
    }

    public function getArrayEma($coin)
    {
        $limit = 89;
        $date = Carbon::now()->subMinute($limit + 1)->format('Y-m-d H:i');
        $candles = Candle::select('close')
            ->where([['coin', $coin], ['date', '<=', $date]])
            ->take($limit)
            ->orderBy('id', 'desc')
            ->get();
        $arrEma = [];
        if (!empty($candles)) {
            $candles = array_reverse($candles->toArray());
            foreach ($candles as $candle) {
                $arrEma[] = $candle;
            }
        }
        return $arrEma;
    }
}
