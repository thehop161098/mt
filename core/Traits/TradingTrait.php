<?php

namespace Core\Traits;

trait TradingTrait
{
    public static function getResult($open, $close)
    {
        $results = config('constants.orders');
        if ($open > $close) return $results['red'];
        if ($open < $close) return $results['green'];
        if ($open === $close) return $results['yellow'];
    }

    public function randomRangeCandle($number, $max, $min, $type = null)
    {
        if ($type === 'plus') {
            return round((float)($number + abs((float)($number) * ($min + lcg_value() * (abs($max - $min))))), 2);
        } else if ($type === 'minus') {
            return round((float)($number - abs((float)($number) * ($min + lcg_value() * (abs($max - $min))))), 2);
        }
        return round((float)(($number + (float)($number) * ($min + lcg_value() * (abs($max - $min))))), 2);
    }
}
