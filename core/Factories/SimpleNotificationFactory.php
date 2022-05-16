<?php

namespace Core\Factories;

use Core\Factories\Notifications\KYCAccount;
use Core\Factories\Notifications\Supported;
use Core\Factories\Notifications\SupportRequest;
use Core\Factories\Notifications\VerifyAccount;
use Core\Factories\Notifications\WithdrawRequest;
use Core\Factories\Notifications\WithdrawTemp;
use Core\Factories\Notifications\InternalWithdraw;
use Core\Factories\Notifications\WithdrawSuccess;
use Core\Factories\Notifications\DepositSuccess;

class SimpleNotificationFactory
{
    public function createNotification($type)
    {
        switch ($type) {
            case config('constants.email.verify_account'):
                $notification = new VerifyAccount();
                break;

            case config('constants.email.withdraw_temp'):
                $notification = new WithdrawTemp();
                break;

            case config('constants.email.internal_withdraw'):
                $notification = new InternalWithdraw();
                break;

            case config('constants.email.withdraw_success'):
                $notification = new WithdrawSuccess();
                break;

            case config('constants.email.deposit_success'):
                $notification = new DepositSuccess();
                break;

            case config('constants.telegram.withdraw_request'):
                $notification = new WithdrawRequest();
                break;

            case config('constants.telegram.kyc_request'):
                $notification = new KYCAccount();
                break;

            case config('constants.telegram.support'):
                $notification = new SupportRequest();
                break;

            case config('constants.email.supported'):
                $notification = new Supported();
                break;

            default:
                $notification = null;
                break;
        }

        return $notification;
    }
}
