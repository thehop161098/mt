<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'content',
        'response',
        'status',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'status_text'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/supports/' . $this->getKey());
    }

    public function getStatusTextAttribute()
    {
        if ($this->status) {
            return 'Supported';
        }
        return "Not Support";
    }
}
