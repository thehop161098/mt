<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\CoinInterface;

class CoinsController extends Controller
{

    private $coinRepository;

    public function __construct(CoinInterface $coinRepository)
    {
        $this->coinRepository = $coinRepository;
    }

    public function getCoins()
    {
        $coins = $this->coinRepository->find();
        return response($coins);
    }
}
