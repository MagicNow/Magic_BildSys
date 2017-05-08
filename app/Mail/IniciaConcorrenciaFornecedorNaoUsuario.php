<?php

namespace App\Mail;

use App\Models\Fornecedor;
use App\Models\QuadroDeConcorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IniciaConcorrenciaFornecedorNaoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $quadroDeConcorrencia;
    public $fornecedor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(QuadroDeConcorrencia $quadroDeConcorrencia, Fornecedor $fornecedor)
    {
        $this->quadroDeConcorrencia = $quadroDeConcorrencia;
        $this->fornecedor = $fornecedor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Solicitação de orçamento - BILD '.date('d/m/Y'))->view('emails.qc.concorrencia-fornecedor-sem-acesso');
    }
}
