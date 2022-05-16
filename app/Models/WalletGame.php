<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletGame extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
