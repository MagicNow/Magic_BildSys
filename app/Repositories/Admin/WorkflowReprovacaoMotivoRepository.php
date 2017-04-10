<?php

namespace App\Repositories\Admin;

use App\Models\WorkflowReprovacaoMotivo;
use InfyOm\Generator\Common\BaseRepository;

class WorkflowReprovacaoMotivoRepository extends BaseRepository
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
        return WorkflowReprovacaoMotivo::class;
    }
}
