<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\Services\OrderService;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use RedisUser;

    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function order(Request $request, $type)
    {
        $res = $this->orderService->create($request, $type);
        return response(['message' => $res['message']], $res['status']);
    }
}
