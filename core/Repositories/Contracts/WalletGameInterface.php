<?php

namespace Core\Repositories\Contracts;

interface WalletGameInterface
{
    public function create($data);

    public function find($code);

    public function where($where);

//    public function update($data, $where, $userId);

}
