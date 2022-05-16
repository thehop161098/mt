<?php

namespace Core\Repositories\Eloquents;

use Core\Repositories\Contracts\SummaryTradeInterface;
use Illuminate\Support\Facades\DB;

class SummaryTradeRepository implements SummaryTradeInterface
{
    private $table = 'orders';

    public function find($condition, $request) {
        $summaryTrades = DB::table($this->table)
            ->select('user_id', 'full_name', 'phone', 'code', DB::raw('SUM(orders.profit) As profit, SUM(orders.amount) As amount'))
            ->join('users', 'user_id', '=', 'users.id')
            ->where('wallet', config('constants.main_wallet'))
            ->whereBetween('date', [$condition['datetime'][0], $condition['datetime'][1]])
            ->groupBy('user_id');

        if (!empty($request->user)) {
            $summaryTrades = $summaryTrades->where(function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->user . '%')
                    ->orWhere('code', 'like', '%' . $request->user . '%')
                    ->orWhere('phone', 'like', '%' . $request->user . '%');
            });
        }
        return $summaryTrades;
    }

    
}
