<?php

namespace App\Repositories\Admin;

use App\Models\CronogramaFisico;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;

class CronogramaFisicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'custo',
        'resumo',
		'torre',
		'pavimento',
		'tarefa',
		'critica',
        'data_inicio',
        'data_termino',
		'concluida'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CronogramaFisico::class;
    }

}
