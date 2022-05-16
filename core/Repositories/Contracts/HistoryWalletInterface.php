<?php

namespace Core\Repositories\Contracts;

interface HistoryWalletInterface
{
    public function find($type);

    public function findAll($type, $whereRelation);

    public function create($data);

    public function searchCommissions($request, $type, $userId);
}
