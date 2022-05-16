<?php

namespace Core\Repositories\Contracts;

interface EmailTemplateInterface
{
    public function find($code);
}
