<?php

namespace Core\Repositories\Contracts;

interface CandleInterface
{
    public function find($coin, $limit);

    public function findCircleHistory($coin, $limit);

    public function findTime($time);

    public function first($id);

    public function update($id, $updates);
}
