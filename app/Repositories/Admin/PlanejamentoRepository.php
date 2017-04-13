<?php

namespace App\Repositories\Admin;

use App\Models\Planejamento;
use InfyOm\Generator\Common\BaseRepository;

class PlanejamentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'tarefa',
        'data',
        'prazo',
        'planejamento_id',
        'data_fim',
        'resumo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Planejamento::class;
    }
}
