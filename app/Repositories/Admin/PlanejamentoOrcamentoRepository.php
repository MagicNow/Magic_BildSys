<?php

namespace App\Repositories\Admin;

use App\Models\PlanejamentoOrcamento;
use InfyOm\Generator\Common\BaseRepository;

class PlanejamentoOrcamentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'planejamento_id',
        'insumo_id',
        'codigo_estruturado',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'trocado_de',
        'insumo_pai',
        'quantidade_compra'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlanejamentoOrcamento::class;
    }
}
