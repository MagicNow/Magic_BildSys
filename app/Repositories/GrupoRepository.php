<?php

namespace App\Repositories;

use App\Models\Grupo;
use InfyOm\Generator\Common\BaseRepository;

class GrupoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nome',
        'grupo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Grupo::class;
    }
}
