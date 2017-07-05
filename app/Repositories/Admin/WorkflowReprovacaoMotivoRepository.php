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

    public function porTipo($tipo_id)
    {
        return $this->model->where(function($query) use ($tipo_id) {
            $query->where('workflow_tipo_id', $tipo_id);
            $query->orWhereNull('workflow_tipo_id');
        })->get();
    }

    public function porTipoForSelect($tipo_id)
    {
        return $this->model->where(function($query) use ($tipo_id) {
            $query->where('workflow_tipo_id', $tipo_id);
            $query->orWhereNull('workflow_tipo_id');
        })
        ->pluck('nome', 'id')
        ->prepend('Motivos...', '');
    }
}
