<?php

namespace App\Repositories;

use App\Models\QuadroDeConcorrenciaItem;
use InfyOm\Generator\Common\BaseRepository;

class QuadroDeConcorrenciaItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'qtd',
        'insumos_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QuadroDeConcorrenciaItem::class;
    }
}
