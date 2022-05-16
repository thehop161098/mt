<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\SummaryTradeInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SummaryTradesController extends Controller
{
    private $summaryTradeRepository;

    public function __construct(SummaryTradeInterface $summaryTradeRepository) {
        $this->summaryTradeRepository = $summaryTradeRepository;
    }

    public function index(Request $request)
    {
        $condition = [];
        if (!empty($request->datetime)) {
            // get today
            if ($this->validateDate($request->datetime)) {
                $fromDateToday = Carbon::createFromFormat('d-m-Y', $request->datetime)->format('Y-m-d 00:00');
                $toDateToday = Carbon::createFromFormat('d-m-Y', $request->datetime)->format('Y-m-d 23:59');
            } else {
                $fromDateToday = Carbon::now()->format('Y-m-d 00:00');
                $toDateToday = Carbon::now()->format('Y-m-d 23:59');
            }
            $condition['datetime'] = [$fromDateToday, $toDateToday];
        } else {
            // get today
            $fromDateToday = Carbon::now()->format('Y-m-d 00:00');
            $toDateToday = Carbon::now()->format('Y-m-d 23:59');

            $condition['datetime'] = [$fromDateToday, $toDateToday];
        }
        $summaryTrades = $this->summaryTradeRepository->find($condition, $request)->paginate(config('constants.PAGINATE_50'));
        $totalAmount = '$' . number_format($summaryTrades->sum('amount'), 2);
        $totalProfit = '$' . number_format($summaryTrades->sum('profit'), 2);
        return view("admin.summary-trades.index",compact('summaryTrades', 'totalAmount', 'totalProfit'));
    }

    function validateDate($date, $format = 'd-m-Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

}
