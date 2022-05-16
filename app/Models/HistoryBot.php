<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryBot extends Model
{
    protected $fillable = [
        'user_id',
        'bot_id',
        'price',
        'commission',
        'time',
        'time_expired',
        'status',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = ['resource_url', 'status_text'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/history-bots/' . $this->getKey());
    }

    public function getStatusTextAttribute()
    {
        if ($this->status) {
            return 'Locking';
        }
        return "Unlock";
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bot()
    {
        return $this->belongsTo('App\Models\AutoBot');
    }
}
