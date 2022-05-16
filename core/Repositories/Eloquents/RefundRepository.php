<?php

namespace Core\Repositories\Eloquents;

use App\Models\Refund;
use Core\Repositories\Contracts\RefundInterface;

class RefundRepository implements RefundInterface
{
    private $model;

    public function __construct(Refund $refund)
    {
        $this->model = $refund;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function where($where)
    {
        return $this->model->where($where);
    }

    public function save($model)
    {
        return $model->save();
    }

    public function getTotalRefundLose($userID)
    {
        return $this->model->where([['user_id', $userID]])->sum('amount');
    }

    public function find($dateToday)
    {
        return $this->model->where('date_expired', '>', $dateToday)
        ->where('user_id', auth()->user()->id)
        ->first();
    }
}
