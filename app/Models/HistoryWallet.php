<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryWallet extends Model
{
    protected $table = 'history_wallets';

    protected $fillable = [
        'user_id',
        'wallet',
        'amount',
        'type',
        'note',
        'is_read',
    ];

    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'route_url'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/history-deposits/' . $this->getKey());
    }

    public function getRouteUrlAttribute()
    {
        $date = date('Y-m-d', strtotime($this->created_at));
        switch ($this->type) {
            case config('constants.type_history.internal_transfer'):
                return route('wallets.exchange');
            case config('constants.type_history.commission_agency_parent'):
                return route('commission.agency') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.commission_daily') . "?from_date=$date&to_date=$date":
                return route('commission.daily') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.refund'):
                return route('history.refund') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.cron_deposit'):
            case config('constants.type_history.discount'):
                return route('wallets.deposit') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.commission_level'):
                return route('commission.master-ib') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.commission_from_child'):
                return route('commission.imcome') . "?from_date=$date&to_date=$date";
            case config('constants.type_history.commission_bot'):
            case config('constants.type_history.commission_bot_daily'):
            case config('constants.type_history.commission_bot_daily_parent'):
                return route('commission.bot') . "?from_date=$date&to_date=$date";
            default:
                return "javascript:void(0)";
        }
    }
}
