<?php

namespace Core\Repositories\Eloquents;

use App\Models\Level;
use Core\Repositories\Contracts\LevelInterface;

class LevelRepository implements LevelInterface
{

    private $model;

    public function __construct(Level $level)
    {
        $this->model = $level;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function where($where)
    {
        return $this->model->where($where)->first();
    }

    public function whereAll($where)
    {
        return $this->model->where($where)->get();
    }
}
