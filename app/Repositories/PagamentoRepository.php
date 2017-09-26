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
        $attributes['obra_id'] = $contrato->obra_id;
        if(isset($attributes['parcelas']) && count($attributes['parcelas'])){
            foreach($attributes['parcelas'] as &$parcela){
                $parcela['valor'] = money_to_float($parcela['valor']);
                if(strlen(trim($parcela['percentual_desconto']))){
                    $parcela['percentual_desconto'] = money_to_float($parcela['percentual_desconto']);
                }
                if(strlen(trim($parcela['valor_desconto']))){
                    $parcela['valor_desconto'] = money_to_float($parcela['valor_desconto']);
                }
                if(strlen(trim($parcela['percentual_juro_mora']))){
                    $parcela['percentual_juro_mora'] = money_to_float($parcela['percentual_juro_mora']);
                }
                if(strlen(trim($parcela['valor_juro_mora']))){
                    $parcela['valor_juro_mora'] = money_to_float($parcela['valor_juro_mora']);
                }
                if(strlen(trim($parcela['percentual_multa']))){
                    $parcela['percentual_multa'] = money_to_float($parcela['percentual_multa']);
                }
                if(strlen(trim($parcela['valor_multa']))){
                    $parcela['valor_multa'] = money_to_float($parcela['valor_multa']);
                }
            }
        }
        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {
        $attributes['valor'] = money_to_float($attributes['valor']);
        if(isset($attributes['parcelas']) && count($attributes['parcelas'])){
            foreach($attributes['parcelas'] as &$parcela){
                $parcela['valor'] = money_to_float($parcela['valor']);
                if(strlen(trim($parcela['percentual_desconto']))){
                    $parcela['percentual_desconto'] = money_to_float($parcela['percentual_desconto']);
                }
                if(strlen(trim($parcela['valor_desconto']))){
                    $parcela['valor_desconto'] = money_to_float($parcela['valor_desconto']);
                }
                if(strlen(trim($parcela['percentual_juro_mora']))){
                    $parcela['percentual_juro_mora'] = money_to_float($parcela['percentual_juro_mora']);
                }
                if(strlen(trim($parcela['valor_juro_mora']))){
                    $parcela['valor_juro_mora'] = money_to_float($parcela['valor_juro_mora']);
                }
                if(strlen(trim($parcela['percentual_multa']))){
                    $parcela['percentual_multa'] = money_to_float($parcela['percentual_multa']);
                }
                if(strlen(trim($parcela['valor_multa']))){
                    $parcela['valor_multa'] = money_to_float($parcela['valor_multa']);
                }
            }
        }

        $model = parent::update($attributes, $id);

        return $model;
    }
}
