<?php

namespace App\Repositories\Admin;

use App\Models\LembreteTipo;
use InfyOm\Generator\Common\BaseRepository;

class LembreteTipoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'dias_prazo_minimo',
        'dias_prazo_maximo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LembreteTipo::class;
    }
}
