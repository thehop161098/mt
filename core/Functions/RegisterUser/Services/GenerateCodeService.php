<?php

namespace Core\Functions\RegisterUser\Services;

use Core\Repositories\Redis\RedisUserRepository;

class GenerateCodeService
{
    private $userRepository;

    public function __construct(RedisUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function generate()
    {
        $code = 'BB'.random_int(100000000, 999999999);
        $countUser = $this->userRepository->where([['code', $code]])->count();
        if ($countUser === 0) {
            return $code;
        }
        return $this->generate();
    }
}
