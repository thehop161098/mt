<?php

namespace Core\Repositories\Eloquents;

use App\Models\Wheel;
use Core\Repositories\Contracts\WheelInterface;

class WheelRepository implements WheelInterface
{
    private $model;

    public function __construct(Wheel $wheel)
    {
        $this->model = $wheel;
    }

    public static function getWheels($sort = 'sort')
    {
        return Wheel::select('name AS text', 'prize', 'sort')->orderBy($sort, 'ASC')->get();
    }
}
