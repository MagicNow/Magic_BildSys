<?php

namespace App\Repositories\Admin;

use App\Models\Obra;
use InfyOm\Generator\Common\BaseRepository;

class ObraRepository extends BaseRepository
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
        return Obra::class;
    }
}
