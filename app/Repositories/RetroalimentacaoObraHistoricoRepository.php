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

    public function getHistoricoByRetroId($id) {

        $r = RetroalimentacaoObraHistorico::with(['userOrigem', 'userDestino', 'statusOrigem', 'statusDestino'])->where('retroalimentacao_obras_id',$id)
            ->orderBy('created_at','desc')
            ->get();

        return $r;
    }
}
