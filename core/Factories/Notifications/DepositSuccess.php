<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Email;
use Core\Decorators\Notification\Telegram;
use Core\Decorators\NotificationConcrete;
use App\Mail\DepositSuccessMail;

class DepositSuccess extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification['email'], new DepositSuccessMail($dataNotification));

        $text = strtr(config('constants.telegram_message.deposit'), [
            '{USER}' => $dataNotification['fullname'],
            '{AMOUNT}' => $dataNotification['amount'],
            '{ADDRESS}' => $dataNotification['address'],
            '{NETWORK}' => $dataNotification['network']
        ]);
        $telegram = new Telegram($email, $text);
        $telegram->send();
    }
}
