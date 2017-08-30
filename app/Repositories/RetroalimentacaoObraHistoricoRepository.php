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
        'retroalimentacao_obras_id',
        'user_id_origem',
        'user_id_destino',
        'status_origem',
        'status_destino',
        'andamento'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RetroalimentacaoObraHistorico::class;
    }
}
