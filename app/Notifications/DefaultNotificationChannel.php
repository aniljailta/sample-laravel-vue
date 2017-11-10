<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DefaultNotificationChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'order_uuid' => $data['order_uuid'] ?? null,
            'type' => $notification->type,
            'data' => $data,
            'read_at' => null
        ]);
    }

}