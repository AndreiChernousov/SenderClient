<?php

namespace NotificationChannels\SenderClient;

use Illuminate\Notifications\Notification;
use NotificationChannels\SenderClient\Exceptions\CouldNotUseNotification;

class SenderClientChannel
{

    public function __construct() {}

    /**
     * @throws \NotificationChannels\SenderClient\Exceptions\CouldNotUseNotification
     */
    public function send(
      mixed $notifiable,
      Notification $notification
    ) {
        $to = $notifiable->routeNotificationFor('SenderClient');
        if (!$to) {
            $to = $notifiable->routes['recipient'];
        }

        if (method_exists($notification, 'toSender')) {
            if ($sender = $notification->toSender($notifiable)) {
                return $sender->to($to)->send();
            }
        } else {
            throw CouldNotUseNotification::missingMethod();
        }
        
        return false;
    }

}
