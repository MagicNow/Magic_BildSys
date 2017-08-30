<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObraHistorico;
use InfyOm\Generator\Common\BaseRepository;

class RetroalimentacaoObraHistoricoRepository extends BaseRepository
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
        return RetroalimentacaoObraHistorico::class;
    }
}
