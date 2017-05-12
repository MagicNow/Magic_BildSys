<?php

namespace App\Repositories\Admin;

use App\Models\CompradorInsumo;
use InfyOm\Generator\Common\BaseRepository;

class CompradorInsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'insumo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CompradorInsumo::class;
    }
}
