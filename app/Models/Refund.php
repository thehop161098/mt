<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'percent',
        'amount',
        'amount_refund',
        'deposit_id',
        'transfer_id',
        'date_expired',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
