<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Email;
use Core\Decorators\NotificationConcrete;
use App\Mail\WithdrawTempMail;

class WithdrawTemp extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification['email'], new WithdrawTempMail($dataNotification));
        $email->send();
    }
}
