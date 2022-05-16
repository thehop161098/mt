<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryDiscount extends Model
{
    protected $fillable = [
        'user_id',
        'discount_id',
        'deposit',
        'discount'

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];
}
