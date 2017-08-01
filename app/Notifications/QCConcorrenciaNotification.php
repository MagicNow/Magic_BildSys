<?php

namespace App\Notifications;

use App\Models\QuadroDeConcorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class QCConcorrenciaNotification extends Notification
{
    use Queueable;

    private $model;

    /**
     * @param QuadroDeConcorrencia $model
     */
    public function __construct(QuadroDeConcorrencia $model)
    {
        $this->model = $model;

        if(!method_exists($this->model, 'concorrenciaNotification')) {
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
        return $this->model->concorrenciaNotification();
    }
}
