<?php

namespace App\Repositories;

use App\Models\ContratoStatus;
use InfyOm\Generator\Common\BaseRepository;

class ContratoStatusRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContratoStatus::class;
    }

    public function comContrato()
    {
        return $this->model->has('contratos')->get();
    }
}
