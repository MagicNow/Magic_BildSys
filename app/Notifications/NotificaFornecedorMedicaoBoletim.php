<?php

namespace App\Notifications;

use App\Models\Contrato;
use App\Models\Fornecedor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificaFornecedorMedicaoBoletim extends Notification
{
    use Queueable;

    public $fornecedor;
    public $boletim;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Fornecedor $fornecedor, $boletim = null)
    {
        $this->fornecedor = $fornecedor;
        $this->boletim = $boletim;
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
            ->greeting('Olá '.$this->fornecedor->nome)
            ->subject('Boletim de Medição de serviço gerado - Bild')
            ->line('Foi gerado o boletim de medição e liberado para faturamento os valores abaixo.')
            ->line('Baixe o documento em anexo para verificar o valor à gerar da Nota Fiscal.')
            ->attach( storage_path('/app/public/') . str_replace('storage/', '', $this->boletim) )
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
