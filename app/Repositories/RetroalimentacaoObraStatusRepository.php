<?php

namespace App\Repositories;

use App\Models\RetroalimentacaoObraStatus;
use InfyOm\Generator\Common\BaseRepository;

class RetroalimentacaoObraStatusRepository extends BaseRepository
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
        return RetroalimentacaoObraStatus::class;
    }
}
