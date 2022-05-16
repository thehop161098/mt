<?php

namespace Core\Repositories\Contracts;

interface OrderInterface
{
    public function select($select);

    public function where($where);

    public function create(array $data);

    public function update(array $data, $id);

    public function groupByWallet($fields, $where);

    public function getSummary($where);

    public function search($request, $type);

    public function coins();

    public function findDashboard($type);

    public function totalAmountFromMondayWeek($userID);

    public function totalAmountYesterday($userID);

    public function getProfitLose($userID);
}
