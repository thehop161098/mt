<?php

namespace App\Http\Controllers\Admin;

use App\Events\ResetCandleManualEvent;
use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\CandleInterface;
use Core\Repositories\Contracts\NewCandleInterface;
use Core\Traits\TradingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ControlCandleController extends Controller
{
    use TradingTrait;
    private $controlCandleRepository;
    private $newCandleRepository;

    public function __construct(CandleInterface $controlCandleRepository, NewCandleInterface $newCandleRepository)
    {
        $this->controlCandleRepository = $controlCandleRepository;
        $this->newCandleRepository = $newCandleRepository;
    }

    public function index(Request $request)
    {
        $time = Carbon::now()->format('Y-m-d H:i');
        $controlCandles = $this->controlCandleRepository->findTime($time);
        return view("admin.control-candle.index", compact('time', 'controlCandles'));
    }

    public function rangeCandle(Request $request, $id, $type)
    {
        if (!empty($request->prize) && $request->prize < 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot enter negative numbers'
            ], 200);
        }

        $checkDate = Carbon::now()->format('Y-m-d H:i');
        $candle = $this->controlCandleRepository->first($id);
        if (!empty($candle)) {

            $coin = $candle->purse;

            if (!empty($coin)) {

                if ($candle->date == $checkDate) {
                    // closeUpdated
                    $result = null;
                    $prize = 1;
                    if ($type === 'equal') {
                        $result = config('constants.orders.yellow');
                        $prize = $request->prize;
                    } elseif ($type === 'plus') {
                        $result = config('constants.orders.green');
                    } elseif ($type === 'minus') {
                        $result = config('constants.orders.red');
                    }

                    event(new ResetCandleManualEvent($candle->coin, $result, $prize));

                    return response()->json([
                        'success' => true,
                        'message' => 'Update successfully!'
                    ]);
                }
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Cannot be updated'
        ]);
    }
}
