<?php

namespace Core\Repositories\Redis;

use App\Models\WalletGame;
use Core\Repositories\Contracts\WalletGameInterface;
use Illuminate\Support\Facades\Redis;

class RedisWalletGameRepository implements WalletGameInterface
{
    private $model;
    private $key = 'walletGames:';

    public function __construct()
    {
        $this->model = new WalletGame();
    }

    public function create($data)
    {
        return WalletGame::create($data);
    }

    public function all($userId)
    {
        $key = $this->key . $userId;
       // Redis::del(Redis::keys($this->key . '*'));
        $wallets = Redis::get($key);
        if (empty($wallets)) {
            $wallets = $this->select(['amount', 'type'])->where([['user_id', $userId]])->toArray();
            if (!empty($wallets)) {
                $wallets[0]['selected'] = true;
                Redis::set($key, json_encode($wallets));
            }
        } else {
            $wallets = json_decode($wallets, true);
        }
        return $wallets;
    }

    public function where($where)
    {
        return $this->model->where($where)->get();
    }

    public function select($select)
    {
        $this->model->select($select);
        return $this;
    }

    public function update($data, $where, $userId)
    {
        $record = $this->where($where)->first();
        if ($record) {
            if (isset($data['amount'])) {
                $data['amount'] += $record->amount;
            }
            if ($record->update($data)) {
                $keyRedis = $this->key . $userId;
                $wallets = Redis::get($keyRedis);
                if (!empty($wallets)) {
                    $wallets = json_decode($wallets, true);
                    foreach ($wallets as $key => $wallet) {
                        if ($wallet['type'] == $record->type) {
                            $wallets[$key]['amount'] = $data['amount'];
                            break;
                        }
                    }
                    Redis::set($keyRedis, json_encode($wallets));
                }
                return true;
            }
        }

        return false;
    }

    public function find($id)
    {
        return WalletGame::find($id);
    }

    public function delRedis($userId)
    {
        $key = $this->key . $userId;
        Redis::del($key);
    }

    public function updateAllWalletUser($userId, $wallets)
    {
        foreach ($wallets as $wallet => $amount) {
            $this->model->where([['user_id', $userId], ['type', $wallet]])->update(['amount' => $amount]);
        }
    }
}
