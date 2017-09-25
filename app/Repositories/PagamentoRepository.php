<?php

namespace App\Repositories;

use App\Models\Contrato;
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

    public function create(array $attributes)
    {
        $attributes['valor'] = money_to_float($attributes['valor']);
        $contrato = Contrato::find($attributes['contrato_id']);
        $attributes['obra_id'] = $contrato->id;
        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {
        $attributes['valor'] = money_to_float($attributes['valor']);
        $model = parent::update($attributes, $id);

        return $model;
    }
}
