<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\OrderInterface;
use Core\Services\OrderService;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use RedisUser;

    private $orderService;
    private $orderRepository;

    public function __construct(OrderService $orderService, OrderInterface $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function order(Request $request, $coin, $wallet, $type)
    {
        $user = $this->getUser();
        $amount = $request->input('amount', 0);
        $res = $this->orderService->handleOrder($user->id, $amount, $coin, $wallet, $type);
        return response(['message' => $res['message']], $res['status']);
    }

    public function getOrdersInMinutes()
    {
        $user = $this->getUser();
        $summary = $this->orderRepository->groupByWallet(['coin'], [['user_id', $user->id], ['wallet', 'main']]);
        return response(['summary' => $summary]);
    }

    public function getSummaryOrder(Request $request, $isLast)
    {
        $walletSelected = $this->getRedisWallet();
        $summary = $this->orderRepository->getSummary([['wallet', $walletSelected['type']]], $isLast);
        return response(['summary' => $summary]);
    }
}
