<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Illuminate\Http\Request;

class LuckyWheelHistoryController extends Controller
{
    private $historyWallet;

    public function __construct(HistoryWalletInterface $historyWallet)
    {
        $this->historyWallet = $historyWallet;
    }

    public function index(Request $request)
    {
        $conds = ['type' => config('constants.type_history.lucky_wheel')];
        $condsRelation = [];
        if ($request->search) {
            $condsRelation = [['email', 'like', '%' . $request->search . '%']];
        }
        $history = $this->historyWallet->findAll($conds, $condsRelation);
        return view("admin.luckyWheelHistory.index", compact('history'));
    }
}
