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
        return RetroalimentacaoObra::class;
    }
}
