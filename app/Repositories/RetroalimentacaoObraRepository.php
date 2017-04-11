<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObra;
use InfyOm\Generator\Common\BaseRepository;

class RetroalimentacaoObraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'user_id',
        'nome',
        'descricao'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RetroalimentacaoObra::class;
    }
}
