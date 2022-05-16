<?php

namespace Core\Repositories\Eloquents;

use App\Models\Discount;
use Core\Repositories\Contracts\DiscountInterface;
use Illuminate\Support\Carbon;

class DiscountRepository implements DiscountInterface
{
    private $model;

    public function __construct(Discount $discount)
    {
        $this->model = $discount;
    }

    public static function getDiscounts()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        return Discount::where([
            ['date_show_image', '<=', $currentDate],
            ['to_date', '>=', $currentDate]
        ])->get();
    }

    public function validDiscount($from_date, $to_date, $id = 0)
    {
        return $this->model->where('id', '<>', $id)
            ->where(function ($q) use ($from_date, $to_date) {
                $q->where([
                    ['from_date', '<=', $from_date],
                    ['to_date', '>=', $to_date],
                ])->orWhere([
                    ['from_date', '>=', $from_date],
                    ['from_date', '<=', $to_date],
                ])->orWhere([
                    ['to_date', '>=', $from_date],
                    ['to_date', '<=', $to_date],
                ]);
            })->first();
    }

    public function getDiscountCurrent($deposit)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        return $this->model->where([
            ['from_date', '<=', $currentDate],
            ['to_date', '>=', $currentDate],
            ['deposit', '<=', $deposit]
        ])->first();
    }

    public function checkArrDelete($discountIds)
    {
        $allowDelete = [];
        if (!empty($discountIds)) {
            foreach ($discountIds as $discountId) {
                $discount = $this->model->find($discountId);
                if ($discount && $discount->history->isEmpty()) {
                    $allowDelete[] = $discountId;
                }
            }
        }
        return $allowDelete;
    }
}
