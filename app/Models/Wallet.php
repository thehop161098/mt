<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'code',
        'private_key',
        'amount',
        'amount_bsc',
        'user_id',
        'cate_id',
        'coin',
    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/wallets/' . $this->getKey());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
