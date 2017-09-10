<?php

namespace App\Repositories\Admin;

use App\Models\MascaraInsumo;
use InfyOm\Generator\Common\BaseRepository;

class MascaraInsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'levantamento_tipos_id',
		'apropriacao',
		'descricao_apropriacao',
		'unidade_sigla'
		
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MascaraInsumo::class;
    }
}
