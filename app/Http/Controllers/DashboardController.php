<?php

namespace App\Http\Controllers;

use Core\Functions\TreeUser\Services\TreeUserService;
use Core\Repositories\Contracts\OrderInterface;
use Core\Repositories\Eloquents\RefundRepository;
use Core\Services\RefundService;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use RedisUser;

    private $orderRepository;
    private $refundRepository;
    private $refundService;
    private $treeUserService;

    public function __construct(
        OrderInterface $orderRepository,
        RefundRepository $refundRepository,
        RefundService $refundService,
        TreeUserService $treeUserService
    ) {
        $this->orderRepository = $orderRepository;
        $this->refundRepository = $refundRepository;
        $this->refundService = $refundService;
        $this->treeUserService = $treeUserService;
    }

    public function index()
    {
        $data = $this->orderRepository->findDashboard(config('constants.main_wallet'));
        // refunds
        $user = $this->getUser();
        $totalLose = $this->refundService->getTotalRefund($user)['amount'];
        $dateToday = Carbon::now()->format('Y-m-d');
        $refund = $this->refundRepository->find($dateToday);

        $totalChildTrade = $this->treeUserService->getTotalTreeTrade($user);

        $winRound = $data['winRound'] ?? 0;
        $loseRound = $data['loseRound'] ?? 0;
        $drawRound = $data['drawRound'] ?? 0;
        $totalWinRound = $winRound + $loseRound + $drawRound;
        return view('frontend.dashboard.index', [
            'orders' => $data['orders'] ?? [],
            'totalRevenue' => $data['totalRevenue'] ?? 0,
//            'totalNetProfit' => $data['totalNetProfit'],
            'totalTradeAmount' => $data['totalTradeAmount'] ?? 0,
            'loseRound' => $loseRound,
            'drawRound' => $drawRound,
            'winRound' => $winRound,
            'totalWinRound' => $totalWinRound === 0 ? 1 : $totalWinRound,
            'totalTrade' => $data['totalTrade'] ?? 0,
            'mainWallet' => $data['mainWallet'] ?? 0,
            'totalLose' => $totalLose,
            'refund' => $refund,
            'totalChildTrade' => $totalChildTrade,
        ]);
    }

    public function postRefund(Request $request)
    {
        $refund = $this->refundService->registerRefundOrder();
        if ($refund && $refund['status'] == 200) {
            $user = $this->getUser();
            $totalLose = $this->refundService->getTotalRefund($user)['amount'];
            // return success
            return response()->json([
                'status' => $refund['status'],
                'message' => $refund['message'],
                'day' => $refund['day'],
                'percent' => $refund['percent'],
                'amount' => $refund['amount'],
                'amount_refund' => $refund['amount_refund'],
                'date_expired' => $refund['date_expired'],
                'totalLose' => '$' . $totalLose
            ], 200);
        } else {
            return response()->json([
                'status' => $refund['status'],
                'message' => $refund['message']
            ], 200);
        }
    }
}
