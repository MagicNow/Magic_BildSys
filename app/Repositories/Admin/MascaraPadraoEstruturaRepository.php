<?php

namespace App\Repositories\Admin;

use App\Models\MascaraPadraoEstrutura;
use InfyOm\Generator\Common\BaseRepository;

class MascaraPadraoEstruturaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'coeficiente',
        'indireto',
        'mascara_padrao_id',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MascaraPadraoEstrutura::class;
    }
}
