<?php

namespace Core\Repositories\Eloquents;

use App\Models\HistoryBot;
use Core\Repositories\Contracts\HistoryBotInterface;
use Illuminate\Support\Carbon;

class HistoryBotRepository implements HistoryBotInterface
{
    private $model;

    public function __construct(HistoryBot $bot)
    {
        $this->model = $bot;
    }

    public function findAll()
    {
        return $this->model->where([
            ['status', true]
        ])->get();
    }

    public function find($id, $userId)
    {
        return $this->model->where([
            ['user_id', $userId],
            ['id', $id],
            ['status', true]
        ])->first();
    }

    public function currentBot($userId)
    {
        return $this->model->where([
            ['user_id', $userId],
            ['time_expired', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
    }

    public function currentBotActive($userId)
    {
        return $this->model->where([
            ['user_id', $userId],
            ['status', true]
        ])->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function currentBotExpired($userId)
    {
        return $this->model->where([
            ['user_id', $userId],
            ['status', true]
        ])->first();
    }

    public function updateExpired($id)
    {
        return $this->model
            ->where('id', $id)
            ->update(array('status' => false));
    }

    public function updateTime($id, $time_expired)
    {
        return $this->model
            ->where('id', $id)
            ->update(array('time_expired' => $time_expired));
    }
}
