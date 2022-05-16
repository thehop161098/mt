<?php

namespace Core\Repositories\Eloquents;

use App\Models\SettingRefund;
use Core\Repositories\Contracts\SettingRefundInterface;

class SettingRefundRepository implements SettingRefundInterface
{
    private $model;

    public function __construct(SettingRefund $settingRefund)
    {
        $this->model = $settingRefund;
    }

    public function where($where)
    {
        return $this->model->where($where);
    }
}
