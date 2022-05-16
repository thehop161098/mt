<?php

namespace Core\Repositories\Eloquents;

use App\Models\AutoBot;
use Core\Repositories\Contracts\AutoBotInterface;

class AutoBotRepository implements AutoBotInterface
{
    private $model;

    public function __construct(AutoBot $bot)
    {
        $this->model = $bot;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }
}
