<?php

namespace Core\Repositories\Eloquents;

use App\Models\PhoneCountry;
use Core\Repositories\Contracts\PhoneCountryInterface;

class PhoneCountryRepository implements PhoneCountryInterface
{

    private $model;

    public function __construct(PhoneCountry $phoneCountry)
    {
        $this->model = $phoneCountry;
    }

    public function all()
    {
        return $this->model->all();
    }
}
