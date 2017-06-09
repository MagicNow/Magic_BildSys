<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkflowNotification extends Notification
{
    use Queueable;

    private $model;

    /**
     * @param Approvable $model
     */
    public function __construct($model)
    {
        $this->model = $model;

        if(!method_exists($this->model, 'workflowNotification')) {
            throw new InvalidArgumentException ('Model [' . get_class($model) . '] does not have the method with notification data');
        }
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
    public function toArray($notifiable)
    {
        return $this->model->workflowNotification();
    }
}
