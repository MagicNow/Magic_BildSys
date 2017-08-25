<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCommonNotification extends Notification
{
    use Queueable;

    private $msg;
    private $link;

    /**
     * @param Approvable $model
     */
    public function __construct($msg, $link)
    {
        $this->msg = $msg;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->msg,
            'link' => $this->link
        ];
    }
}
