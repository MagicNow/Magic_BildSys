<?php

namespace App\Mail;

use App\Models\Fornecedor;
use App\Models\QuadroDeConcorrencia;
use App\Models\TemplateEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgradeceParticipacaoQC extends Mailable
{
    use Queueable, SerializesModels;

    public $fornecedor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Fornecedor $fornecedor)
    {
        $this->fornecedor = $fornecedor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $model = new TemplateEmail();
        $r = $model->find(5);

        $r->template = str_replace("[FORNECEDOR_NOME]", $this->fornecedor->nome, $r->template);


        return $this->subject('Agradecimento Participação - BILD '.date('d/m/Y'))->view('emails.body-email-base')->with([
            'text' => $r->template
        ]);
    }
}
