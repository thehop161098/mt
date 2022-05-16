<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wheel extends Model
{
    protected $fillable = [
        'name',
        'prize',
        'sort',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/wheels/'.$this->getKey());
    }
}
