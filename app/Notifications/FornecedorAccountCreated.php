<?php

namespace App\Notifications;

use App\Models\TemplateEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FornecedorAccountCreated extends Notification
{
    use Queueable;

    private $password = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        $model = new TemplateEmail();
        $r = $model->find(4);

        $r->template = str_replace("[FORNECEDOR_NOME]", $notifiable->name, $r->template);
        $r->template = str_replace("[FORNECEDOR_EMAIL]", $notifiable->email, $r->template);
        $r->template = str_replace("[FORNECEDOR_SENHA]", $this->password, $r->template);

        return (new MailMessage)
            ->subject('Conta de acesso no sistema da Bild Desenvolvimento Imobiliário')
            ->view('emails.body-email-base',['text' => $r->template]);
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
