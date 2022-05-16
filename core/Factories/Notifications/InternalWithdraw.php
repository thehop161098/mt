<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Email;
use Core\Decorators\Notification\Telegram;
use Core\Decorators\NotificationConcrete;
use App\Mail\InternalWithdrawMail;

class InternalWithdraw extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification['email'], new InternalWithdrawMail($dataNotification));

        $text = strtr(config('constants.telegram_message.transfer'), [
            '{USER_FROM}' => $dataNotification['user_from'],
            '{AMOUNT}' => number_format($dataNotification['amount'], 2),
            '{USER_TO}' => $dataNotification['user_to']
        ]);
        $telegram = new Telegram($email, $text);
        $telegram->send();
    }
}
