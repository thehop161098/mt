<?php

namespace Core\Repositories\Eloquents;

use App\Models\Candle;

class CandleRepository
{
    private $table = 'candles';
    private $model;

    public function __construct(Candle $order)
    {
        $this->model = $order;
    }

    public function where($conditions)
    {
        return $this->model->where($conditions);
    }

    public function select($select)
    {
        return $this->model->select($select);
    }
}
