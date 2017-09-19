<?php

namespace App\Repositories;

use App\Models\CarteirasSla;
use InfyOm\Generator\Common\BaseRepository;

class CarteirasSlaRepository extends BaseRepository
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
        return CarteirasSla::class;
    }
	
}
