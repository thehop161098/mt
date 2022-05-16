<?php

namespace Core\Interfaces;

interface UserInterface
{
    public function update($request);
    public function changePassword($request);
}
