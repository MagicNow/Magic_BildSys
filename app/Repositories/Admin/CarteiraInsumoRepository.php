<?php

namespace App\Repositories\Admin;

use App\Models\CarteiraInsumo;
use InfyOm\Generator\Common\BaseRepository;

class CarteiraInsumoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carteira_id',
        'insumo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarteiraInsumo::class;
    }
	
}
