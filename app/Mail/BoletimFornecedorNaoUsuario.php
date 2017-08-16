<?php

namespace App\Mail;

use App\Models\Fornecedor;
use App\Models\TemplateEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BoletimFornecedorNaoUsuario extends Mailable
{
    use Queueable, SerializesModels;
    
    public $fornecedor;
    public $arquivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Fornecedor $fornecedor, $arquivo = null)
    {
        $this->fornecedor = $fornecedor;
        $this->arquivo = $arquivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $model = new TemplateEmail();
        $r = $model->find(2);

        $r->template = str_replace("[FORNECEDOR_NOME]", $this->fornecedor->nome, $r->template);
        
        return $this->subject('Boletim de Medição de serviço - '.date('m/Y').' - Bild')
            ->attach( storage_path('/app/public/') . str_replace('storage/', '', $this->arquivo) )
            ->view('emails.body-email-base')
            ->with(['text' => $r->template]);
    }
}
