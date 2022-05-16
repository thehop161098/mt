<?php

namespace Core\Repositories\Eloquents;

use App\Models\Wallet;
use Core\Repositories\Contracts\WalletInterface;

class WalletRepository implements WalletInterface
{
    public function find($code)
    {
        return Wallet::firstWhere('code', $code);
    }

    public function where($conditions)
    {
        return Wallet::firstWhere($conditions);
    }

    public function first(array $conditions)
    {
        return Wallet::where($conditions)->first();
    }

    public function all(array $conditions)
    {
        return Wallet::where($conditions)->get();
    }

    public function getAllCoinUserInConfig($user, array $coins)
    {
        return Wallet::where([['user_id', $user->id]])->whereIn('coin', $coins)->get();
    }

    public function getTwentyWalletOfUser(array $arrId, $limit = 20)
    {
        return Wallet::where([['user_id', '<>', null]])
            ->whereNotIn('id', $arrId)
            ->orderBy('id', 'DESC')
            ->skip(0)
            ->take($limit)
            ->get();
    }

    public function updateAmount($wallet, $amount, $field = 'amount')
    {
        return Wallet::where('id', $wallet->id)->update(array($field => $amount));
    }
}
