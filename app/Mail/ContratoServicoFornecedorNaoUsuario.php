<?php

namespace App\Mail;

use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\QuadroDeConcorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratoServicoFornecedorNaoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $contrato;
    public $fornecedor;
    public $arquivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contrato $contrato, $arquivo = null)
    {
        $this->contrato = $contrato;
        $this->fornecedor = $contrato->fornecedor;
        $this->arquivo = $arquivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contrato ' . $this->contrato->id . ' Bild - [Assinar]')
            ->attach( storage_path('/app/public/') . str_replace('storage/', '', $this->arquivo) )
            ->view('emails.contratos.contrato-fornecedor-sem-acesso');
    }
}
