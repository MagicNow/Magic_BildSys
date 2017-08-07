<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkflowNotificationAprovado extends Notification
{
    use Queueable;

    private $model;
    private $aprovado;

    /**
     * @param Approvable $model
     */
    public function __construct($model, $aprovado)
    {
        $this->model = $model;
        $this->aprovado = $aprovado;

        if(!method_exists($this->model, 'workflowNotificationDone')) {
            throw new InvalidArgumentException ('Model [' . get_class($model) . '] does not have the method with notification done data');
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
        return $this->model->workflowNotificationDone($this->aprovado);
    }
}
