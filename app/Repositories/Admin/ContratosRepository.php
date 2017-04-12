<?php

namespace App\Repositories\Admin;

use App\Models\Contrato;
use InfyOm\Generator\Common\BaseRepository;

class ContratosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'data',
        'valor',
        'arquivo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contrato::class;
    }
}
