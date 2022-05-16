<?php

namespace Core\Repositories\Contracts;

interface NewCandleInterface
{
    public function where($coin);

    public function update($coin, $newClose);
}
