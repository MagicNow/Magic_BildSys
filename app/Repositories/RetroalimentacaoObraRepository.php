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
        'origem',
        'categoria',
        'situacao_atual',
        'situacao_proposta',
        'data_inclusao'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RetroalimentacaoObra::class;
    }
}
