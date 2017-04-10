<?php

namespace App\Repositories\Admin;

use App\Models\WorkflowAlcada;
use InfyOm\Generator\Common\BaseRepository;

class WorkflowAlcadaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'workflow_tipo_id',
        'nome',
        'ordem'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WorkflowAlcada::class;
    }
}
