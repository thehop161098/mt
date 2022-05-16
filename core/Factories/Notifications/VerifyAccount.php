<?php

namespace Core\Factories\Notifications;

use Core\Decorators\Notification\Email;
use Core\Decorators\NotificationConcrete;
use App\Mail\VerifyMail;

class VerifyAccount extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification->email, new VerifyMail($dataNotification));
        $email->send();
    }
}
