<?php

namespace Core\Repositories\Contracts;

interface LevelInterface
{
    public function all();

    public function where($where);
}
