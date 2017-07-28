<?php

namespace App\Mail;

use App\Models\Contrato;
use App\Models\Fornecedor;
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
        return $this->subject('Boletim de Medição de serviço - '.date('m/Y').' - Bild')
            ->attach( storage_path('/app/public/') . str_replace('storage/', '', $this->arquivo) )
            ->view('emails.contratos.boletim-fornecedor-sem-acesso');
    }
}
