<?php

namespace App\Repositories;

use App\Models\Notafiscal;
use InfyOm\Generator\Common\BaseRepository;

class NotafiscalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contrato_id',
        'solicitacao_entrega_id',
        'xml',
        'codigo',
        'versao',
        'natureza_operacao',
        'data_emissao',
        'data_saida',
        'cnpj',
        'razao_social',
        'fantasia',
        'cnpj_destinatario'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notafiscal::class;
    }
}
