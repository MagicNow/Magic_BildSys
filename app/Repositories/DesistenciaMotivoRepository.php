<?php

namespace App\Repositories;

use App\Models\DesistenciaMotivo;
use InfyOm\Generator\Common\BaseRepository;

class DesistenciaMotivoRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DesistenciaMotivo::class;
    }
}
