<?php

namespace App\Notifications;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificaFornecedorContratoServico extends Notification
{
    use Queueable;

    public $contrato;
    public $arquivo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Contrato $contrato, $arquivo = null)
    {
        $this->contrato = $contrato;
        $this->arquivo = $arquivo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Olá '.$this->contrato->fornecedor->nome)
            ->subject('Contrato ' . $this->contrato->id . ' Bild - [Assinar]')
            ->line('O contrato foi gerado e é necessário sua assinatura em todas as folhas')
            ->line('para dar seguimento no processo de contratação.')
            ->line('Baixe o arquivo em anexo, assine e nos envie.')
            ->attach( storage_path('/app/public/') . str_replace('storage/', '', $this->arquivo) )
            ->line('Obrigado');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
