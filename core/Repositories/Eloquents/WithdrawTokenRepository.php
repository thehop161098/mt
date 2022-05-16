<?php

namespace Core\Repositories\Eloquents;

use App\Models\WithdrawToken;
use Core\Repositories\Contracts\WithdrawTokenInterface;

class WithdrawTokenRepository implements WithdrawTokenInterface
{
    public function first(array $conditions)
    {
        return WithdrawToken::where($conditions)->first();
    }

    public function all(array $conditions)
    {
        return WithdrawToken::where($conditions)->get();
    }

    public function save($data)
    {
        $withdrawToken = new WithdrawToken();
        $withdrawToken->user_id = $data->user_id;
        $withdrawToken->type = $data->type;
        $withdrawToken->coin = $data->coin;
        $withdrawToken->amount = $data->amount;
        $withdrawToken->status = $data->status;
        if(isset($data->txHash) && !empty($data->txHash)) {
            $withdrawToken->txHash = $data->txHash;
        }
        $withdrawToken->fee = $data->fee;
        $withdrawToken->from_wallet_address = $data->from_wallet_address;
        $withdrawToken->wallet_address = $data->wallet_address;
        $withdrawToken->save();
        return $withdrawToken->id;
    }
}
