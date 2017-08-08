<?php

namespace App\Repositories;

use App\Models\Regional;
use InfyOm\Generator\Common\BaseRepository;

class RegionalRepository extends BaseRepository
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
        return Regional::class;
    }
}
