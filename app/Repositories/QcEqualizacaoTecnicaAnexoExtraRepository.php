<?php

namespace App\Repositories;

use App\Models\QcEqualizacaoTecnicaAnexoExtra;
use InfyOm\Generator\Common\BaseRepository;

class QcEqualizacaoTecnicaAnexoExtraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'arquivo',
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcEqualizacaoTecnicaAnexoExtra::class;
    }
}
