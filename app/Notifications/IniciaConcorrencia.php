<?php

namespace App\Notifications;

use App\Models\Fornecedor;
use App\Models\QuadroDeConcorrencia;
use App\Models\TemplateEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IniciaConcorrencia extends Notification
{
    use Queueable;

    public $quadroDeConcorrencia;
    public $fornecedor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(QuadroDeConcorrencia $quadroDeConcorrencia, Fornecedor $fornecedor)
    {
        $this->quadroDeConcorrencia = $quadroDeConcorrencia;
        $this->fornecedor = $fornecedor;
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

        /*$tipo_subject = 'Novo ';
        $tipo_line = 'Existe um novo ';

        if($this->quadroDeConcorrencia->rodada_atual === 1) {
            $tipo_subject = 'Novo ';
            //$tipo_line = 'Existe uma nova rodada do ';
        }*/

        $model = new TemplateEmail();
        $r = $model->find(6);

        $r->template = str_replace("[FORNECEDOR_NOME]", $this->fornecedor->nome, $r->template);

        return (new MailMessage)
            ->subject('Novo Quadro de Concorrência ' . $this->quadroDeConcorrencia->id . ' - Bild '.date('d/m/Y'))
            ->view('emails.body-email-base',['text' => $r->template]);

        /*return (new MailMessage)
            ->subject($tipo_subject.'Quadro de Concorrência ' . $this->quadroDeConcorrencia->id . ' - Bild '.date('d/m/Y') )
            ->line($tipo_line.'Quadro de Concorrência e você foi convidado à participar.')
            ->action('Envie sua proposta', url('/'))
            ->line('Agradecemos antecipadamente pela sua atenção!');*/
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
