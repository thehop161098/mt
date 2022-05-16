<?php

namespace Core\Factories;


class NotificationFactory
{
    public $simpleNotificationFactory;

    public function __construct()
    {
        $this->simpleNotificationFactory = new SimpleNotificationFactory();
    }

    public function produceNotification($type, $dataNotification)
    {
        $notification = $this->simpleNotificationFactory->createNotification($type);
        if ($notification) {
            $notification->send($dataNotification);
        }

        return $notification;
    }
}
