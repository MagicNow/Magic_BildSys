<?php

namespace App\Repositories\Admin;

use App\Models\Levantamento;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;

class LevantamentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'apropriacao',
        'insumo_id',
		'torre',
		'andar',
		'pavimento',
		'trecho',
		'comodo',
		'parede',
		'apartamento',
		'trecho_parede',
		'personalizavel',
		'quantidade',
		'perda',
        'data_inicio',
        'data_termino'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Levantamento::class;
    }

}
