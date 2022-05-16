<?php

namespace Core\Repositories\Eloquents;

use App\Models\HistoryWallet;
use Core\Repositories\Contracts\HistoryWalletInterface;
use DateTime;
use Illuminate\Support\Carbon;

class HistoryWalletRepository implements HistoryWalletInterface
{

    private $historyWallet;

    public function __construct(HistoryWallet $historyWallet)
    {
        $this->historyWallet = $historyWallet;
    }

    public static function getTopWheel()
    {
        return HistoryWallet::where([
            ['type', config('constants.type_history.lucky_wheel')],
            ['amount', '>', 999]
        ])->orderByDesc('created_at')->limit(5)->latest()->get();
    }

    public function where($where)
    {
        return $this->historyWallet->where($where);
    }

    public function find($type)
    {
        return $this->historyWallet->where('type', $type)
            ->where('user_id', auth()->user()->id)
            ->paginate(config('constants.PAGINATE_50'));
    }

    public function create($data)
    {
        return $this->historyWallet->create($data);
    }

    public function findAll($where, $whereRelation = [])
    {
        $history = $this->historyWallet->where($where);
        if (!empty($whereRelation)) {
            $history = $history->whereHas('user', function ($query) use ($whereRelation) {
                return $query->where($whereRelation);
            });
        }
        return $history->orderBy('created_at',
            'desc')->paginate(config('constants.PAGINATE_50'));
    }

    public function searchCommissions($request, $type, $userId)
    {
        if (!empty($request->from_date) && !empty($request->to_date)) {
            // get today
            if ($this->validateDateRange($request->from_date, $request->to_date, 'Y-m-d')) {
                $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d 00:00:00');
                $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d 23:59:59');
            } else {
                $fromDate = Carbon::now()->format('Y-m-d 00:00:00');
                $toDate = Carbon::now()->format('Y-m-d 23:59:59');
            }
        } else {
            $fromDate = Carbon::now()->format('Y-m-d 00:00:00');
            $toDate = Carbon::now()->format('Y-m-d 23:59:59');
        }

        $commissions = $this->historyWallet->where([['user_id', $userId]]);

        if ($type === config('constants.type_history.cron_deposit')) {
            $commissions = $commissions->whereIn('type',
                [config('constants.type_history.cron_deposit'), config('constants.type_history.discount')]);
        } elseif ($type === config('constants.type_history.commission_bot')) {
            $commissions = $commissions->whereIn('type',
                [
                    config('constants.type_history.commission_bot'),
                    config('constants.type_history.commission_bot_daily'),
                    config('constants.type_history.commission_bot_daily_parent')
                ]);
        } else {
            $commissions = $commissions->where('type', $type);
        }

        $commissions = $commissions->orderBy('created_at', 'DESC')
            ->select('amount', 'note', 'created_at', 'type');
        if ($type !== config('constants.type_history.commission_agency_parent')) {
            $commissions = $commissions->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $commissions = $commissions->paginate(config('constants.PAGINATE_50'));
        return $commissions;
    }

    function validateDateRange($fromDate, $toDate, $format = 'd-m-Y')
    {
        $d = DateTime::createFromFormat($format, $fromDate);
        $d2 = DateTime::createFromFormat($format, $toDate);
        return $d && $d->format($format) === $fromDate && $d2 && $d2->format($format) === $toDate;
    }

    public function summaryHistoryWallet($userId)
    {
        return $this->historyWallet->where('user_id', $userId)
            ->groupBy('user_id')
            ->selectRaw('user_id, 
            SUM(CASE WHEN wallet = "' . config('constants.trial_wallet') . '" THEN amount ELSE 0 END) as ' . config('constants.trial_wallet') . ',
            SUM(CASE WHEN wallet = "' . config('constants.discount_wallet') . '" THEN amount ELSE 0 END) as ' . config('constants.discount_wallet') . ',
            SUM(CASE WHEN wallet = "' . config('constants.main_wallet') . '" THEN amount ELSE 0 END) as ' . config('constants.main_wallet') . '
        ')->first();
    }

    public function readAll($where = [])
    {
        return $this->historyWallet->where($where)->update(['is_read' => 1]);
    }

    public function isInsertHistoryToDay($where, $fromDate, $toDate)
    {
        $totalRows = $this->historyWallet->where($where)->whereBetween('created_at', [$fromDate, $toDate])->count();
        return $totalRows > 0 ? false : true;
    }

    public function getDepositDiscountNewest($where)
    {
        return $this->historyWallet->where($where)->latest()->first();
    }

    public function isSpinWheel($where)
    {
        $fromDate = Carbon::now()->format('Y-m-d 00:00:00');
        $toDate = Carbon::now()->format('Y-m-d 23:59:59');
        return $this->historyWallet->where($where)->whereBetween('created_at', [$fromDate, $toDate])->count();
    }
}
