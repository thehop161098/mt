<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundCandle extends Model
{
    protected $fillable = [
        'coin',
        'round',
    ];

    public $timestamps = false;
}
