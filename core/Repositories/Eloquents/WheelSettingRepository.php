<?php

namespace Core\Repositories\Eloquents;

use App\Models\WheelSetting;
use Core\Repositories\Contracts\WheelSettingInterface;

class WheelSettingRepository implements WheelSettingInterface
{
    private $model;

    public function __construct(WheelSetting $wheelSetting)
    {
        $this->model = $wheelSetting;
    }

    public function getSettingWheel($amount)
    {
        return $this->model->where('amount', '<=', $amount)->latest()->first();
    }

}
