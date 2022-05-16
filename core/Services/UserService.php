<?php

namespace Core\Services;

use Core\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Auth;

class UserService
{
    private $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function generate2faSecret()
    {
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $data = [
            'google2fa_enable' => 0,
            'google2fa_secret' => $google2fa->generateSecretKey()
        ];
        $generate2fa = ['secret' => '', 'google2fa_url' => ''];
        $user = Auth::user();
        if ($this->userRepository->update($data, $user)) {
            $generate2fa['secret'] = $data['google2fa_secret'];
            $generate2fa['google2fa_url'] = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $data['google2fa_secret']
            );
        }
        return $generate2fa;
    }

    public function toggle2fa($data)
    {
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $user = Auth::user();
        $status = $user->google2fa_enable ? 0 : 1;
        $valid = $google2fa->verifyKey($user->google2fa_secret, $data['secret']);
        if ($valid && $this->userRepository->update(['google2fa_enable' => $status], $user)) {
            return [
                'success' => true,
                'status' => $status,
            ];
        } else {
            return [
                'success' => false,
                'status' => $status,
            ];
        }
    }

    
}
