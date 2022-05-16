<?php

namespace Core\Decorators\Notification;

use Core\Decorators\NotificationDecorate;
use Core\Interfaces\INotificationDecorate;

class Telegram extends NotificationDecorate
{
    private $text = null;

    public function __construct(INotificationDecorate $notification, $text)
    {
        parent::__construct($notification);
        $this->text = $text;
    }

    public function send()
    {
        $this->notification->send();
        $this->sendNotification();

    }

    public function sendNotification()
    {
        try {
            $url = "https://api.telegram.org/bot" . env('BOT_TELEGRAM') . "/sendMessage?chat_id=" . env('GROUP_TELEGRAM') . "&text=" . $this->text . "&parse_mode=html";
            return file_get_contents($url);
        } catch (\Exception $e) {
            return null;
        }
    }
}
