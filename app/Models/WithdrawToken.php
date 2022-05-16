<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawToken extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'withdraws_token';

    protected $fillable = [
        'user_id',
        'type',
        'coin',
        'amount',
        'status',
        'txtHash',
        'fee',
        'from_wallet_address',
        'wallet_address',
    ];
}
