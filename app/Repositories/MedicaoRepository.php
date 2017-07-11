<?php

namespace App\Repositories;

use App\Models\Medicao;
use InfyOm\Generator\Common\BaseRepository;

class MedicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mc_medicao_previsao_id',
        'qtd',
        'periodo_inicio',
        'periodo_termino',
        'user_id',
        'aprovado',
        'obs'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Medicao::class;
    }
}
