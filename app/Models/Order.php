<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'candle_id',
        'user_id',
        'wallet',
        'coin',
        'type',
        'amount',
        'profit',
        'open',
        'close',
        'date',
        'is_handle'
    ];

    public function coinAlias()
    {
        return $this->belongsTo('App\Models\Coin', 'coin', 'name');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')
        ->select('email');
    }
}
