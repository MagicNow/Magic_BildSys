<?php

namespace App\Repositories\Admin;

use App\Models\Carteira;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\Admin\PlanejamentoCompraRepository;

class CarteiraRepository extends BaseRepository
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
        return Carteira::class;
    }

    public function findByUser($user_id)
    {
        return $this->model->whereHas('users', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->orderBy('nome','ASC')
        ->get();
    }
	
	
	public function comInsumoOrcamentoObra($obra_id)
    {
        return $this->model
            ->whereHas('insumos', function($query) use ($obra_id) {
                $query
                    ->join('orcamentos', 'orcamentos.insumo_id', '=', 'insumos.id')											
                    ->where('orcamentos.obra_id', $obra_id);
            })
            ->orderBy('nome','ASC')
            ->get();
    }
	
}
