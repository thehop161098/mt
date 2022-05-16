<?php

namespace Core\Repositories\Eloquents;

use App\Models\WalletGame;
use Core\Repositories\Contracts\WalletGameInterface;

class WalletGameRepository implements WalletGameInterface
{
    public function create($data)
    {
        return WalletGame::create($data);
    }

    public function find($id)
    {
        return WalletGame::find($id);
    }

    public function where($conditions)
    {
        return WalletGame::firstWhere($conditions);
    }

    public function update($data, $where)
    {
        // TODO: Implement update() method.
    }


}
