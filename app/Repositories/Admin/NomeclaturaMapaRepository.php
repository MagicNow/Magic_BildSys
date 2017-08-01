<?php

namespace App\Repositories\Admin;

use App\Models\NomeclaturaMapa;
use InfyOm\Generator\Common\BaseRepository;

class NomeclaturaMapaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'tipo',
        'apenas_cartela',
        'apenas_unidade'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return NomeclaturaMapa::class;
    }
}
