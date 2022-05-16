<?php

namespace Core\Repositories\Contracts;

interface WalletInterface
{
    public function find($code);

    public function where($conditions);

}
