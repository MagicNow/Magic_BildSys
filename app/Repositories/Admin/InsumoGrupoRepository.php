<?php

namespace App\Repositories\Admin;

use App\Models\InsumoGrupo;
use InfyOm\Generator\Common\BaseRepository;

class InsumoGrupoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo_identificador',
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InsumoGrupo::class;
    }
}
