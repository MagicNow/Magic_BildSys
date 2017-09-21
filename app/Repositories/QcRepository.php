<?php

namespace App\Repositories;

use App\Models\Qc;
use InfyOm\Generator\Common\BaseRepository;

class QcRepository extends BaseRepository
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
        return Qc::class;
    }
	
}
