<?php

namespace Core\Repositories\Contracts;

interface RefundInterface
{
    public function where($where);

    public function create($data);

}
