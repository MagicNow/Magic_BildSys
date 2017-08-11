<?php

namespace App\Repositories\Admin;

use App\Models\CarteiraTipoEqualizacaoTecnica;
use InfyOm\Generator\Common\BaseRepository;

class CarteiraTipoEqualizacaoTecnicaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carteira_id',
        'tipo_equalizacao_tecnica_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarteiraTipoEqualizacaoTecnica::class;
    }

}
