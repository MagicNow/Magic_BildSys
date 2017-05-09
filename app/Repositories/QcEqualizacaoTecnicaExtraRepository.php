<?php

namespace App\Repositories;

use App\Models\QcEqualizacaoTecnicaExtra;
use InfyOm\Generator\Common\BaseRepository;

class QcEqualizacaoTecnicaExtraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'nome',
        'descricao',
        'obrigatorio'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcEqualizacaoTecnicaExtra::class;
    }
}
