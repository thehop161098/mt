<?php

namespace Core\Decorators\Notification;

use Core\Decorators\NotificationDecorate;
use Core\Interfaces\INotificationDecorate;
use Illuminate\Support\Facades\Mail;

class Email extends NotificationDecorate
{
    private $classNotification;
    private $to = [];

    public function __construct(INotificationDecorate $notification, $to, $classNotification)
    {
        parent::__construct($notification);
        $this->to = $to;
        $this->classNotification = $classNotification;
    }

    public function send()
    {
        $this->notification->send();
        $this->sendNotification();

    }

    public function sendNotification()
    {
        Mail::to($this->to)->queue($this->classNotification);
    }
}
