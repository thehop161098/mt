<?php

namespace App\Http\Controllers;

use Core\Repositories\Redis\RedisCoinRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseController;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Contracts\OrderInterface;
use Illuminate\Support\Facades\View;

class HistoryController extends BaseController
{
    private $orderRepository;

    public function __construct(
        OrderInterface $orderRepository,
        HistoryWalletInterface $historyRepository
    ) {
        $this->historyRepository = $historyRepository;
        $this->orderRepository = $orderRepository;

        $coinRepository = new RedisCoinRepository();
        $coins = $coinRepository->find();
        View::share(['coins' => $coins]);
    }

    public function demo(Request $request)
    {
        $orders = $this->orderRepository->search($request, config('constants.trial_wallet'));
        return view('frontend.history.demo', compact('orders'));
    }

    public function live(Request $request)
    {
        $orders = $this->orderRepository->search($request, config('constants.main_wallet'));
//        dd($orders->toArray());
        return view('frontend.history.live', compact('orders'));
    }

    public function promotion(Request $request)
    {
        $orders = $this->orderRepository->search($request, config('constants.discount_wallet'));
        return view('frontend.history.promotion', compact('orders'));
    }

    public function refund(Request $request)
    {
        $refunds = $this->historyRepository->searchCommissions($request, config('constants.type_history.refund'),
            auth()->user()->id);
        $this->historyRepository->readAll([
            ['user_id', auth()->user()->id],
            ['type', config('constants.type_history.refund')]
        ]);
        return view('frontend.history.refund', compact('refunds'));
    }

}
