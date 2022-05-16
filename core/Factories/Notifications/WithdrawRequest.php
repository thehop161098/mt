<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Telegram;
use Core\Decorators\NotificationConcrete;

class WithdrawRequest extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $text = strtr(config('constants.telegram_message.withdraw_request'), $dataNotification);
        $telegram = new Telegram($notification, $text);
        $telegram->send();
    }
}
