<?php

namespace App\Http\Controllers;

// use Core\Services\BotService;
use Core\Services\DepositService;
use Core\Traits\RedisUser;

class TestController extends Controller
{
    use RedisUser;
    
    private $service;

    public function __construct(DepositService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->delUserCache(348);
        // $this->service->cronjobPlusAmount();
    }
}
