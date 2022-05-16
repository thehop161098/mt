<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Candle extends Model
{
    protected $fillable = [
        'round',
        'open',
        'close',
        'high',
        'low',
        'date',
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'date', 'date')
            ->where('wallet', '<>', config('constants.trial_wallet'))
            ->where('coin', $this->coin)
            ->groupBy('user_id')
            ->selectRaw('user_id, 
            SUM(CASE WHEN type = "' . config('constants.orders.red') . '" THEN amount ELSE 0 END) as amount_sell,
            SUM(CASE WHEN type = "' . config('constants.orders.green') . '" THEN amount ELSE 0 END) as amount_higher,
            SUM(CASE WHEN type = "' . config('constants.orders.yellow') . '" THEN amount ELSE 0 END) as amount_balance
        ')
            ->get();
    }

    public function summary()
    {
        return Order::where([
            ['date', $this->date],
            ['coin', $this->coin],
            ['wallet', '<>', config('constants.trial_wallet')]
        ])
            ->selectRaw(' 
            SUM(CASE WHEN type = "' . config('constants.orders.red') . '" THEN amount ELSE 0 END) as amount_sell,
            SUM(CASE WHEN type = "' . config('constants.orders.green') . '" THEN amount ELSE 0 END) as amount_higher,
            SUM(CASE WHEN type = "' . config('constants.orders.yellow') . '" THEN amount ELSE 0 END) as amount_balance')
            ->first();
    }

    public function purse()
    {
        return $this->belongsTo('App\Models\Coin', 'coin', 'name');
    }
}
