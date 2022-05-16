<?php

namespace Core\Traits;

use Core\Repositories\Redis\RedisUserRepository;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

trait RedisUser
{
    private $user;
    private $userRepository;
    private $walletGameRepository;

    public function saveDBWallets($walletSelected, $user_id)
    {
        $walletGameRepository = $this->getWalletGameRepository();
        $wallets = $walletGameRepository->where($user_id);
        if (!empty($wallets)) {
            foreach ($wallets as $key => $wallet) {
                if ($wallet['type'] === $walletSelected['type']) {
                    $wallets[$key] = $walletSelected;
                    break;
                }
            }
            $key = 'walletGames:' . $user_id;
            Redis::set($key, json_encode($wallets));
        }
        return true;
    }

    public function getWalletGameRepository()
    {
        if ($this->walletGameRepository) return $this->walletGameRepository;
        return new RedisWalletGameRepository();
    }

    public function saveRedisWallets($type)
    {
        $wallets = $this->getRedisWallets();
        $walletSelected = null;
        foreach ($wallets as $key => $wallet) {
            if (isset($wallet['selected'])) unset($wallets[$key]['selected']);
            if ($wallet['type'] == $type) {
                $wallets[$key]['selected'] = true;
                $walletSelected = $wallets[$key];
            }
        }
        if (!empty($walletSelected)) {
            $user = $this->getUser();
            $key = "walletGames:$user->id";
            Redis::set($key, json_encode($wallets));
        }
        return $walletSelected;
    }

    public function getRedisWallets()
    {
        $user = $this->getUser();
        $walletGameRepository = $this->getWalletGameRepository();
        return $walletGameRepository->all($user->id);
    }

    public function getUser()
    {
        $userRepository = $this->getUserRepository();
        return $userRepository->find(Auth::user());
    }

    public function getUserRepository()
    {
        if ($this->userRepository) return $this->userRepository;
        return new RedisUserRepository();
    }

    public function getRedisWallet($type = null)
    {
        $wallets = $this->getRedisWallets();
        foreach ($wallets as $wallet) {
            if ($type === null) {
                if (isset($wallet['selected'])) return $wallet;
            } else {
                if ($wallet['type'] === $type) return $wallet;
            }
        }
        return null;
    }

    public function delUserCache($userId)
    {
        $userRepository = $this->getUserRepository();
        return $userRepository->del($userId);
    }
}
