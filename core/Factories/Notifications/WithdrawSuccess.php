<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Email;
use Core\Decorators\NotificationConcrete;
use App\Mail\WithdrawSuccessMail;

class WithdrawSuccess extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification['email'], new WithdrawSuccessMail($dataNotification));
        $email->send();
    }
}
