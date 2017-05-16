<?php

namespace App\Repositories\Admin;

use App\Models\SolicitacaoInsumo;
use InfyOm\Generator\Common\BaseRepository;

class SolicitacaoInsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'unidade_sigla',
        'codigo',
        'insumo_grupo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SolicitacaoInsumo::class;
    }
}
