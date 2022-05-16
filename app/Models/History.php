<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'historys';
    protected $fillable = [
        'user_id',
        'amount_before',
        'amount_after',
    ];
}
