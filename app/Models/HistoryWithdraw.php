<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryWithdraw extends Model
{
    protected $table = 'history_withdraw';

    protected $fillable = [
        'user_id',
        'wallet',
        'coin',
        'network',
        'amount_fee',
        'amount_convert',
        'amount',
        'code',
        'status',
        'reason',
        'tx_hash'
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['resource_url', 'status_text', 'wallet_text'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/history-withdraws/' . $this->getKey());
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === config('constants.status_withdraw.pending')) {
            return config('constants.status_withdraw.pending_text');
        } elseif ($this->status === config('constants.status_withdraw.approved')) {
            return config('constants.status_withdraw.approved_text');
        } elseif ($this->status === config('constants.status_withdraw.reject')) {
            return config('constants.status_withdraw.reject_text');
        }
        return "";
    }

    public function getWalletTextAttribute()
    {
        if ($this->wallet === config('constants.main_wallet')) {
            return 'BIT';
        } elseif ($this->wallet === config('constants.discount_wallet')) {
            return 'DISCOUNT ACCOUNT';
        }
        return "";
    }
}
