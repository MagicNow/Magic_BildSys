<?php

namespace App\Repositories;

use App\Models\Requisicao;
use InfyOm\Generator\Common\BaseRepository;

class RequisicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Requisicao::class;
    }
}
