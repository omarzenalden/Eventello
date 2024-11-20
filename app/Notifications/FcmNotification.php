<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kawankoding\Fcm\Message\PayloadDataBuilder;
use Kawankoding\Fcm\Message\PayloadNotificationBuilder;
use Kawankoding\Fcm\Message\Topics;

class FcmNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $notificationBuilder = new PayloadNotificationBuilder('Event Reminder');
        $notificationBuilder->setBody('Reminder for your event: ' . $this->event->title)
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['event_id' => $this->event->id]);

        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        return [
            'notification' => $notification,
            'data' => $data,
            'topic' => $notifiable->fcm_token,
        ];
    }
}
