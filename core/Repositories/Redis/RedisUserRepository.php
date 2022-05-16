<?php

namespace Core\Repositories\Redis;

use App\Models\User;
use Core\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Redis;

class RedisUserRepository implements UserInterface
{
    public $model;

    public function __construct()
    {
        $user = new User();
        $this->model = $user;
    }

    public function update($data, $model)
    {
        $model->fill($data);
        if (isset($data['google2fa_secret'])) $model->google2fa_secret = $data['google2fa_secret'];
        if ($model->save()) {
            $key = 'user:' . $model->id;
            Redis::set($key, $model);
            return true;
        }
        return false;
    }

    public function find($model)
    {
        $key = 'user:' . $model->id;
        $userRedis = Redis::get($key);
        if (!empty($userRedis)) {
            $userRedis = json_decode($userRedis, true);
            $user = new User($userRedis);
            $user->id = $userRedis['id'];
            return $user;
        }
        Redis::set($key, $model);
        return $model;
    }

    public function where($where)
    {
        return $this->model->where($where)->get();
    }

    public function first(array $where)
    {
        return $this->model->where($where)->first();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function del($userId)
    {
        $key = 'user:' . $userId;
        Redis::del($key);
        return true;
    }
}
