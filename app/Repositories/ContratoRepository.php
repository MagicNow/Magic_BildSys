<?php

namespace App\Repositories;

use App\Models\Contrato;
use InfyOm\Generator\Common\BaseRepository;

class ContratoRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contrato::class;
    }
}
