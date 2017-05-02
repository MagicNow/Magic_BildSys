<?php

namespace App\Repositories;

use App\Models\QuadroDeConcorrencia;
use InfyOm\Generator\Common\BaseRepository;

class QuadroDeConcorrenciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'qc_status_id',
        'obrigacoes_fornecedor',
        'obrigacoes_bild',
        'rodada_atual'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QuadroDeConcorrencia::class;
    }
}
