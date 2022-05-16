<?php

namespace Core\Repositories\Eloquents;

use App\Models\Advertisement;
use Core\Repositories\Contracts\AdvertisementInterface;

class AdvertisementRepository implements AdvertisementInterface
{
    private $model;

    public function __construct(Advertisement $ads)
    {
        $this->model = $ads;
    }

    public static function getAdvertisements()
    {
        return Advertisement::where([
            ['publish', true]
        ])->orderBy('sort', 'asc')->get();
    }

}
