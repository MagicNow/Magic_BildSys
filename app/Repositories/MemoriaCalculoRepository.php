<?php

namespace App\Repositories;

use App\Models\MemoriaCalculo;
use InfyOm\Generator\Common\BaseRepository;

class MemoriaCalculoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'padrao',
        'user_id',
        'modo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MemoriaCalculo::class;
    }
}
