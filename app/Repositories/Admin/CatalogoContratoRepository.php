<?php

namespace App\Repositories\Admin;

use App\Models\CatalogoContrato;
use InfyOm\Generator\Common\BaseRepository;

class CatalogoContratoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fornecedor_id',
        'data',
        'valor',
        'arquivo',
        'periodo_inicio',
        'periodo_termino',
        'valor_minimo',
        'valor_maximo',
        'qtd_minima',
        'qtd_maxima'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CatalogoContrato::class;
    }
}
