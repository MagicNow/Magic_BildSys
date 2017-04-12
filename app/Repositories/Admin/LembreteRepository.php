<?php

namespace App\Repositories\Admin;

use App\Models\Lembrete;
use InfyOm\Generator\Common\BaseRepository;

class LembreteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lembrete_tipo_id',
        'user_id',
        'nome',
        'dias_prazo_minimo',
        'dias_prazo_maximo',
        'insumo_grupo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Lembrete::class;
    }
}
