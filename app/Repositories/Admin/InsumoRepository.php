<?php

namespace App\Repositories\Admin;

use App\Models\Insumo;
use InfyOm\Generator\Common\BaseRepository;

class InsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'unidade_sigla',
        'codigo',
        'insumo_grupo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Insumo::class;
    }
}
