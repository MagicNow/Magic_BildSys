<?php

namespace App\Repositories;

use App\Models\Lpu;
use InfyOm\Generator\Common\BaseRepository;

class LpuRepository extends BaseRepository
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
        return Lpu::class;
    }
	
}
