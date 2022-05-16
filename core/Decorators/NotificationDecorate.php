<?php

namespace Core\Decorators;

use Core\Interfaces\INotificationDecorate;

abstract class NotificationDecorate implements INotificationDecorate
{
    protected $notification;

    public function __construct(INotificationDecorate $notification)
    {
        $this->notification = $notification;
    }
}

