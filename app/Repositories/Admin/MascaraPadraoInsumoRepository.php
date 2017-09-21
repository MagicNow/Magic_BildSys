<?php

namespace App\Repositories\Admin;

use App\Models\MascaraPadraoInsumo;
use InfyOm\Generator\Common\BaseRepository;

class MascaraPadraoInsumoRepository extends BaseRepository
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
        return MascaraPadraoInsumo::class;
    }
}
