<?php

namespace Core\Repositories\Eloquents;

use App\Models\HistoryDiscount;
use Core\Repositories\Contracts\HistoryDiscountInterface;

class HistoryDiscountRepository implements HistoryDiscountInterface
{
    private $model;

    public function __construct(HistoryDiscount $historyDiscount)
    {
        $this->model = $historyDiscount;
    }

    public function isDiscount($userId, $discountId)
    {
        return $this->model->where([
            ['user_id', $userId],
            ['discount_id', $discountId]
        ])->count();
    }

    public function create($data)
    {
        return HistoryDiscount::create($data);
    }
}
