<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Services\LuckyWheelService;
use Core\Traits\RedisUser;

class LuckyWheelController extends Controller
{
    use RedisUser;

    private $luckyWheelService;

    public function __construct(LuckyWheelService $luckyWheelService)
    {
        $this->luckyWheelService = $luckyWheelService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function spin()
    {
        $user = $this->getUser();
        $res = $this->luckyWheelService->spin($user->id);
        return response($res);
    }
}
