<?php

namespace Core\Repositories\Eloquents;

use App\Models\Order;
use Core\Repositories\Contracts\OrderInterface;
use Core\Traits\CacheTraint;
use Core\Traits\RedisUser;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class OrderRepository implements OrderInterface
{
    use RedisUser, CacheTraint;
    private $table = 'orders';
    private $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function select($select)
    {
        return $this->model->select($select);
    }

    public function where($conditions)
    {
        return $this->model->where($conditions);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $where)
    {
        return Order::where($where)->update($data);
    }

    public function groupByWallet($fields, $where)
    {
        $fields[] = DB::raw('SUM(amount) AS amount');
        $where[] = ['date', date('Y-m-d H:i')];
        return DB::table($this->table)
            ->select('coin', 'type', DB::raw('sum(amount) as amount'))
            ->where($where)
            ->groupBy('coin', 'type')->get();
    }

    public function getSummary($where, $isLast = false)
    {
        if ($isLast) {
            $date = date('Y-m-d H:i', strtotime('-1 minutes'));
            $where[] = ['date', $date];
        } else {
            $where[] = ['date', date('Y-m-d H:i')];
        }
        return DB::table($this->table)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->where($where)
            ->groupBy('type')
            ->pluck('count', 'type')->all();
    }

    public function search($request, $type)
    {
        if (!empty($request->from_date) && !empty($request->to_date)) {
            // get today
            if ($this->validateDateRange($request->from_date, $request->to_date, 'Y-m-d')) {
                $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d 00:00');
                $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d 23:59');
            } else {
                $fromDate = Carbon::now()->format('Y-m-d 00:00');
                $toDate = Carbon::now()->format('Y-m-d 23:59');
            }
        } else {
            $fromDate = Carbon::now()->format('Y-m-d 00:00');
            $toDate = Carbon::now()->format('Y-m-d 23:59');
        }

        $orders = $this->model->where('wallet', $type)
            ->where('user_id', auth()->user()->id)
            ->whereBetween('date', [$fromDate, $toDate])
            ->select('wallet', 'coin', 'type', 'date', 'open', 'close', DB::raw('sum(amount) as amount'),
                DB::raw('sum(profit) as profit'))
            ->groupBy('wallet', 'coin', 'type', 'date', 'open', 'close')
            ->orderBy('date', 'DESC');

        if (!empty($request->currency)) {
            $orders = $orders->where('coin', $request->currency);
        }

        $orders = $orders->paginate(config('constants.PAGINATE_50'));
        return $orders;
    }

    function validateDateRange($fromDate, $toDate, $format = 'd-m-Y')
    {
        $d = DateTime::createFromFormat($format, $fromDate);
        $d2 = DateTime::createFromFormat($format, $toDate);
        return $d && $d->format($format) === $fromDate && $d2 && $d2->format($format) === $toDate;
    }

    public function coins()
    {
        $coins = [];
        foreach (config('coins') as $coin) {
            $coins[$coin->name] = $coin->alias;
        }
        return $coins;
    }

    public function findDashboard($type)
    {
        // get date in week
        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
        $endDate = Carbon::now()->format('Y-m-d 23:59:59');
        // get Orders
        $data['orders'] = $this->model->where('wallet', $type)
            ->where('user_id', auth()->user()->id)
            ->select('wallet', 'coin', 'type', 'date', 'open', 'close', DB::raw('sum(amount) as amount'),
                DB::raw('sum(profit) as profit'))
            ->groupBy('wallet', 'coin', 'type', 'date', 'open', 'close')
            ->orderBy('date', 'DESC')
            ->paginate(config('constants.PAGINATE_10'));
        // get totalRevenue
        $data['totalRevenue'] = $this->model->where('wallet', $type)
            ->where('user_id', auth()->user()->id)
            ->sum('profit');
        // get Net profit
//        $data['totalNetProfit'] = $this->model->where([
//            ['wallet', '=', $type],
//            ['profit', '>', 0],
//            ['user_id', auth()->user()->id]
//        ])->sum('profit');
        // get Total trade amount
        $totalTradeAmount = $this->getCache(config('constants.cache.order_in_week') . auth()->user()->id);
        if ($type !== config('constants.main_wallet') || $totalTradeAmount === null) {
            $totalTradeAmount = $this->model->where('wallet', $type)
                ->where('user_id', auth()->user()->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
            $this->saveCache(config('constants.cache.order_in_week') . auth()->user()->id, $totalTradeAmount);
        }
        $data['totalTradeAmount'] = $totalTradeAmount;
        // Lose round
        $data['loseRound'] = $this->model->where([
            ['wallet', '=', $type],
            ['profit', '>', 0],
            ['user_id', '=', auth()->user()->id],
            ['type', '=', config('constants.orders.red')]
        ])->count();
        // Draw round
        $data['drawRound'] = $this->model->where([
            ['wallet', '=', $type],
            ['profit', '>', 0],
            ['user_id', '=', auth()->user()->id],
            ['type', '=', config('constants.orders.yellow')]
        ])->count();
        // Win round
        $data['winRound'] = $this->model->where([
            ['wallet', '=', $type],
            ['profit', '>', 0],
            ['user_id', '=', auth()->user()->id],
            ['type', '=', config('constants.orders.green')]
        ])->count();
        //Total Trade
        $data['totalTrade'] = $this->model->where([
            ['wallet', '=', $type],
            ['user_id', '=', auth()->user()->id]
        ])->count();
        //Main Wallet
        $data['mainWallet'] = $this->getRedisWallet(config('constants.main_wallet'))['amount'];

        return $data;
    }

    public function totalAmountFromMondayWeek($userID)
    {
        // get date
        if (Carbon::now()->startOfWeek()->isToday()) {
            $startDate = Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d 00:00:00');
            $endDate = Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d 23:59:59');
        } else {
            $startDate = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
            $endDate = Carbon::yesterday()->format('Y-m-d 23:59:59');
        }
        // get total
        $total = $this->model->where('candle_id', '<>', null)
            ->whereIn('user_id', $userID)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('wallet', config('constants.main_wallet'))
            ->sum('amount');
        return $total;
    }

    public function totalAmountYesterday($userID)
    {
        // get date
        $startDate = Carbon::yesterday()->format('Y-m-d 00:00:00');
        $endDate = Carbon::yesterday()->format('Y-m-d 23:59:59');
        // get total
        $total = $this->model->where('candle_id', '<>', null)
            ->whereIn('user_id', $userID)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('wallet', config('constants.main_wallet'))
            ->sum('amount');
        return $total;
    }

    public function getProfitLose($userID)
    {
        return $this->model->where([
            ['user_id', $userID],
            ['wallet', config('constants.main_wallet')]
        ])->sum('profit');
    }

    public function getTotalAmountInWeek($userID)
    {
        $totalAmount = $this->getCache(config('constants.cache.order_in_week') . $userID);
        if ($totalAmount !== null) {
            return $totalAmount;
        }

        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
        $endDate = Carbon::now()->format('Y-m-d 23:59:59');
        $totalAmount = $this->model->select('amount')->where('wallet', config('constants.main_wallet'))
            ->where('user_id', $userID)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        $this->saveCache(config('constants.cache.order_in_week') . $userID, $totalAmount);

        return $totalAmount;
    }

    public function getOrderError()
    {
        $current_time = Carbon::now()->format('Y-m-d H:i');

        return $this->model->where([
            ['candle_id', null],
            ['wallet', '<>', config('constants.trial_wallet')],
            ['is_handle', 0],
            ['date', '<>', $current_time]
        ])->get();
    }

    public function getTotalAmountUser($userID)
    {
        $totalAmount = $this->getCache(config('constants.cache.order_all') . $userID);
        if ($totalAmount !== null) {
            return $totalAmount;
        }

        $totalAmount = $this->model->where('wallet', config('constants.main_wallet'))
            ->where('user_id', $userID)
            ->sum('amount');

        $this->saveCache(config('constants.cache.order_all') . $userID, $totalAmount);

        return $totalAmount;
    }
}
