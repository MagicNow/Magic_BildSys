<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObraCategoria;
use InfyOm\Generator\Common\BaseRepository;

class RetroalimentacaoObraCategoriaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RetroalimentacaoObraCategoria::class;
    }
}
