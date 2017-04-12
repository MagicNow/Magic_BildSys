<?php

namespace App\Repositories\Admin;

use App\Models\Admin\ContratoInsumo;
use InfyOm\Generator\Common\BaseRepository;

class ContratoInsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contrato_id',
        'insumo_id',
        'qtd',
        'valor_unitario',
        'valor_total'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoInsumo::class;
    }
}
