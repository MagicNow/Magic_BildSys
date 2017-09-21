<?php

namespace App\Repositories;

use App\Models\Pagamento;
use InfyOm\Generator\Common\BaseRepository;

class PagamentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contrato_id',
        'obra_id',
        'numero_documento',
        'fornecedor_id',
        'data_emissao',
        'valor',
        'pagamento_condicao_id',
        'documento_tipo_id',
        'notas_fiscal_id',
        'enviado_integracao',
        'integrado'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pagamento::class;
    }
}
