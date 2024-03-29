<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingRefund extends Model
{
    protected $fillable = [
        'day',
        'amount',
        'percent',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/setting-refunds/'.$this->getKey());
    }
}
