<?php

namespace App\Mail;

use App\Models\Fornecedor;
use App\Models\QuadroDeConcorrencia;
use App\Models\TemplateEmail;
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

        $model = new TemplateEmail();
        $r = $model->find(2);

        $table = '';


        $r->template = str_replace("[FORNECEDOR_NOME]", $this->fornecedor->nome, $r->template);

        foreach ($this->quadroDeConcorrencia->itens as $item) {

            $table .= "<p>".$item->insumo->nome." | Qtd.".$item->qtd.". ".$item->insumo->unidade_sigla."</p>";
        }

        $r->template = str_replace("[TABELA_PRODUTOS]", $table, $r->template);


        return $this->subject('Solicitação de orçamento - BILD '.date('d/m/Y'))->view('emails.qc.concorrencia-fornecedor-sem-acesso')->with([
            'text' => $r->template
        ]);
    }
}
