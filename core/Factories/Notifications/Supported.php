<?php

namespace Core\Factories\Notifications;

use App\Mail\SupportedMail;
use Core\Decorators\Notification\Email;
use Core\Decorators\NotificationConcrete;

class Supported extends Notification
{
    public function send($dataNotification)
    {
        $notification = new NotificationConcrete();
        $email = new Email($notification, $dataNotification['email'], new SupportedMail($dataNotification));
        $email->send();
    }
}
