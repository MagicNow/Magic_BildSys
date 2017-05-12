<?php

namespace App\Notifications;

use App\Models\QuadroDeConcorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IniciaConcorrencia extends Notification
{
    use Queueable;

    public $quadroDeConcorrencia;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(QuadroDeConcorrencia $qc)
    {
        $this->quadroDeConcorrencia = $qc;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this->quadroDeConcorrencia->rodada_atual === 1) {
            return (new MailMessage)
                    ->subject('Novo Quadro de Concorrência ' . $this->quadroDeConcorrencia->id . ' - Bild '.date('d/m/Y') )
                    ->line('Existe um novo Quadro de Concorrência e você foi convidado à participar.')
                    ->action('Envie sua proposta', url('/'))
                    ->line('Agradecemos antecipadamente pela sua atenção!');
        }

        return (new MailMessage)
                    ->subject('Nova rodada do Quadro de Concorrência ' . $this->quadroDeConcorrencia->id . ' - Bild '.date('d/m/Y') )
                    ->line('Existe uma nova rodada neste Quadro de Concorrência e você foi convidado à participar.')
                    ->action('Envie sua proposta', url('/'))
                    ->line('Agradecemos antecipadamente pela sua atenção!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
