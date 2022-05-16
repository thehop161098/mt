<?php

namespace Core\Repositories\Contracts;

interface CoinInterface
{
    public function find();

    public function getCoinSelected();

    public function update();
}
