<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $guard = 'user';

    protected $fillable = [
        'full_name',
        'code',
        'level',
        'email',
        'email_verified_at',
        'avatar',
        'identity_card_after',
        'identity_card_before',
        'portrait',
        'verify',
        'intro',
        'phone',
        'referral_code',
        'password',
        'google2fa_enable',
        'phone_country'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'resource_url',
        'referral_url',
        'verify_text',
        'is_view',
        'images',
        'total_withdrawal',
        'total_deposit',
        'total_order',
        'total_transfer',
        'total_transfer_to',
        'total_profit',
        'icon_kyc',
        'class_kyc',
        'icon_level',
        'is_spin'
    ];

    public function wallets()
    {
        return $this->hasMany('App\Models\Wallet', 'user_id');
    }

    public function walletGames()
    {
        return $this->hasMany('App\Models\WalletGame', 'user_id');
    }

    public function walletMain()
    {
        return $this->hasOne('App\Models\WalletGame', 'user_id')
            ->where('type', config('constants.main_wallet'));
    }

    public function walletDiscount()
    {
        return $this->hasOne('App\Models\WalletGame', 'user_id')
            ->where('type', config('constants.discount_wallet'));
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'referral_code', 'code');
    }

    public function childrenHasAgency()
    {
        return $this->hasMany(self::class, 'referral_code', 'code')
            ->where('level', '>', 0);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'referral_code', 'code');
    }

    public function refund()
    {
        $currentDate = date('Y-m-d');
        return $this->hasOne('App\Models\Refund', 'user_id')
            ->where('date_expired', '>=', $currentDate);
    }

    public function childrenHasVerify()
    {
        return $this->hasMany(self::class, 'referral_code', 'code')
            ->where('verify', config('constants.verify_user'));
    }

    public function historyNotifications()
    {
        $arrType = [
            config('constants.type_history.internal_transfer'),
            config('constants.type_history.commission_daily'),
            config('constants.type_history.refund'),
            config('constants.type_history.cron_deposit'),
            config('constants.type_history.commission_level'),
            config('constants.type_history.commission_from_child'),
            config('constants.type_history.commission_agency_parent'),
            config('constants.type_history.repay'),
            config('constants.type_history.discount'),
            config('constants.type_history.lucky_wheel'),
            config('constants.type_history.commission_bot'),
            config('constants.type_history.commission_bot_daily'),
            config('constants.type_history.commission_bot_daily_parent')
        ];
        return $this->hasMany('App\Models\HistoryWallet', 'user_id')
            ->where('is_read', 0)
            ->whereIn('type', $arrType)->orderBy('id', 'DESC');
    }

    public function getResourceUrlAttribute()
    {
        return url('/admin/users/' . $this->getKey());
    }

    public function getReferralUrlAttribute()
    {
        return url('/register?referral_code=' . $this->code);
    }

    /* ************************ ACCESSOR ************************* */

    public function getAvatar()
    {
        $imgUserDefault = 'images/profile/default_user.png';
        $pathAvatar = config('constants.path_user') . 'user' . auth()->user()->id . '/' . $this->avatar;
        if (empty($this->avatar) || !is_file($pathAvatar)) {
            return $imgUserDefault;
        }
        return $pathAvatar;
    }

    public function getVerifyTextAttribute()
    {
        switch ($this->verify) {
            case config('constants.pending_verify_user'):
                $verify = config('constants.pending_verify_user_text');
                break;
            case config('constants.verify_user'):
                $verify = config('constants.verify_user_text');
                break;
            default:
                $verify = config('constants.not_verify_user_text');
                break;
        }
        return $verify;
    }

    public function getIsViewAttribute()
    {
        return $this->verify !== config('constants.not_verify_user');
    }

    public function getImagesAttribute()
    {
        $portrait = config('constants.path_user') . 'user' . $this->id . '/' . $this->portrait;

        if (empty($this->portrait) || !is_file($portrait)) {
            $portrait = 'frontend/images/icons/icKYCSelfie.png';
        }

        $identity_card_before = config('constants.path_user') . 'user' . $this->id . '/' . $this->identity_card_before;
        if (empty($this->identity_card_before) || !is_file($identity_card_before)) {
            $identity_card_before = 'frontend/images/icons/icKYCFront.png';
        }

        $identity_card_after = config('constants.path_user') . 'user' . $this->id . '/' . $this->identity_card_after;
        if (empty($this->identity_card_after) || !is_file($identity_card_after)) {
            $identity_card_after = 'frontend/images/icons/icKYCBack.png';
        }
        return [
            'identity_card_after' => '/' . $identity_card_after,
            'identity_card_before' => '/' . $identity_card_before,
            'portrait' => '/' . $portrait
        ];
    }

    public function getTotalWithdrawalAttribute()
    {
        return $this->withdrawals()->sum('amount');
    }

    public function withdrawals()
    {
        return $this->hasMany('App\Models\HistoryWithdraw', 'user_id')
            ->where('status', config('constants.status_withdraw.approved'));
    }

    public function getTotalDepositAttribute()
    {
        return $this->deposits()->sum('amount');
    }

    public function deposits()
    {
        return $this->hasMany('App\Models\HistoryWallet', 'user_id')
            ->where('type', config('constants.type_history.cron_deposit'));
    }

    public function getTotalOrderAttribute()
    {
        return $this->orders()->sum('amount');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id')
            ->where('wallet', '<>', config('constants.trial_wallet'));
    }

    public function getTotalProfitAttribute()
    {
        return $this->orders()->sum('profit');
    }

    public function getTotalTransferAttribute()
    {
        return $this->transfers()->sum('amount');
    }

    public function transfers()
    {
        return $this->hasMany('App\Models\HistoryWallet', 'user_id')
            ->where([['type', config('constants.type_history.internal_transfer')], ['amount', '>', 0]]);
    }

    public function getTotalTransferToAttribute()
    {
        return $this->transfersTo()->sum('amount') * (-1);
    }

    public function transfersTo()
    {
        return $this->hasMany('App\Models\HistoryWallet', 'user_id')
            ->where([['type', config('constants.type_history.internal_transfer')], ['amount', '<', 0]]);
    }

    public function getIconKycAttribute()
    {
        if ($this->verify === config('constants.verify_user')) {
            return asset('frontend/images/icons/icVerifySuccess.svg');
        }
        if ($this->verify === config('constants.pending_verify_user')) {
            return asset('frontend/images/icons/icVerifyPending.svg');
        }
        return asset('frontend/images/icons/icNotVerify.svg');
    }

    public function getClassKycAttribute()
    {
        if ($this->verify === config('constants.verify_user')) {
            return 'verifySuccess';
        }
        if ($this->verify === config('constants.pending_verify_user')) {
            return 'verifyPending';
        }
        return 'notVerify';
    }

    public function getIconLevelAttribute()
    {
        $path = "frontend/images/icons/icAgency$this->level.png";
        return asset($path);
    }

    public function getIsSpinAttribute()
    {
        $fromDate = Carbon::now()->format('Y-m-d 00:00:00');
        $toDate = Carbon::now()->format('Y-m-d 23:59:59');
        return $this->hasOne('App\Models\HistoryWallet', 'user_id')
            ->where('type', config('constants.type_history.lucky_wheel'))
            ->whereBetween('created_at', [$fromDate, $toDate])->count() ? false : true;
    }
}
