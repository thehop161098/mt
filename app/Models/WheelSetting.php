<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WheelSetting extends Model
{
    protected $fillable = [
        'amount',
        'prize',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/wheel-settings/'.$this->getKey());
    }
}
