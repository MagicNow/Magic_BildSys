<?php

namespace App\Repositories\Admin;

use App\Models\InsumoGrupo;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\Admin\PlanejamentoCompraRepository;

class InsumoGrupoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo_identificador',
        'nome'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InsumoGrupo::class;
    }

    public function comLembretesComItensDeCompraPorUsuario($user_id, $lembrete_tipo_id = 1)
    {
        return $this->model->whereHas('lembretes')
            ->whereHas('lembretes', function($query) use ($user_id, $lembrete_tipo_id) {
                $query
                    ->join('insumos', 'insumos.insumo_grupo_id', '=', 'lembretes.insumo_grupo_id')
                    ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
                    ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
                    ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
                    ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
                    ->whereNull('planejamentos.deleted_at')
                    ->where('lembretes.lembrete_tipo_id', $lembrete_tipo_id)
                    ->where('obra_users.user_id', $user_id)
                    ->whereRaw(PlanejamentoCompraRepository::EXISTE_ITEM_PRA_COMPRAR);
            })
            ->get();
    }

    public function enable($id)
    {
        return $this->update(['active' => true], $id);
    }

    public function disable($id)
    {
        return $this->update(['active' => false], $id);
    }
}
