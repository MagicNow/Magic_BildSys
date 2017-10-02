<?php

namespace App\Repositories;

use App\Models\QcAnexo;
use InfyOm\Generator\Common\BaseRepository;

class QcAnexoRepository extends BaseRepository
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
        return QcAnexo::class;
    }
	
}
