<?php

namespace App\Repositories\Admin;

use App\Models\CronogramaFisico;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\Lembrete;

class CronogramaFisicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'tarefa',
        'data',
        'prazo',
        'planejamento_id',
        'data_fim',
        'resumo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CronogramaFisico::class;
    }

    public function comLembretesComItensDeCompraPorUsuario(
        $user_id,
        $lembrete_tipo_id = 1
    ) {
        return Lembrete::select('planejamentos.*')
            ->join('insumo_grupos', 'insumo_grupos.id', '=', 'lembretes.insumo_grupo_id')
            ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
            ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
            ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
            ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
            ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
            ->whereNull('planejamentos.deleted_at')
            ->where('lembretes.lembrete_tipo_id', $lembrete_tipo_id)
            ->where('obra_users.user_id', $user_id)
            ->whereRaw(PlanejamentoCompraRepository::existeItemParaComprar())
            ->groupBy('planejamentos.id')
            ->orderBy(DB::raw('trim(tarefa)'),'ASC')
            ->get();
    }
}
