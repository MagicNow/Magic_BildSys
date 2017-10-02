<?php

namespace App\Repositories\Admin;

use App\Models\PreOrcamento;
use InfyOm\Generator\Common\BaseRepository;

class PreOrcamentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'codigo_insumo',
        'insumo_id',
        'servico_id',
        'grupo_id',
        'unidade_sigla',
        'coeficiente',
        'indireto',
        'terreo_externo_solo',
        'terreo_externo_estrutura',
        'terreo_interno',
        'primeiro_pavimento',
        'segundo_ao_penultimo',
        'cobertura_ultimo_piso',
        'atico',
        'reservatorio',
        'qtd_total',
        'preco_unitario',
        'preco_total',
        'referencia_preco',
        'obs',
        'porcentagem_orcamento',
        'orcamento_tipo_id',
        'ativo',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'user_id',
        'descricao'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PreOrcamento::class;
    }
}
