<?php

namespace Core\Repositories\Contracts;

interface UserInterface
{
    public function find($user);

    public function update($data, $id);

    public function where($where);

}
