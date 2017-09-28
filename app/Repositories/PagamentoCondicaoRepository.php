<?php

namespace App\Repositories;

use App\Models\PagamentoCondicao;
use InfyOm\Generator\Common\BaseRepository;

class PagamentoCondicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
        'codigo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PagamentoCondicao::class;
    }
}
