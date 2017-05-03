<?php

namespace App\Repositories;

use App\Models\QcTipoEqualizacaoTecnica;
use InfyOm\Generator\Common\BaseRepository;

class QcTipoEqualizacaoTecnicaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'tipo_equalizacao_tecnica_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcTipoEqualizacaoTecnica::class;
    }
}
