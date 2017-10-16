<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\ContratoStatus;
use App\Models\Estoque;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\Obra;
use Illuminate\Http\Request;

class GestaoEstoqueController extends AppBaseController
{

    public function index(Request $request)
    {
        $estoque = Estoque::select(
                'estoque.id',
                'estoque.obra_id',
                'estoque.insumo_id',
                'estoque.qtde',
                'insumos.insumo_grupo_id'
            )
            ->join('insumos', 'insumos.id', '=', 'estoque.insumo_id')
            ->with('obra', 'insumo')->get();

        $obras = Obra::whereIn('id', $estoque->pluck('obra_id', 'obra_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $grupo_insumos = InsumoGrupo::whereIn('id', $estoque->pluck('insumo_grupo_id', 'insumo_grupo_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();
        
        $insumos = Insumo::whereIn('id', $estoque->pluck('insumo_id', 'insumo_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        if($request->obra_id) {
            $estoque = $estoque->where('obra_id', $request->obra_id);
        }

        if($request->insumo_grupo_id) {
            $estoque = $estoque->where('insumo_grupo_id', $request->insumo_grupo_id);
        }
        
        if($request->insumo_id) {
            $estoque = $estoque->where('insumo_id', $request->insumo_id);
        }

        return view('gestao_estoque.index', compact('estoque', 'obras', 'insumos', 'grupo_insumos'));
    }

    public function estoqueMinimo(Request $request)
    {
        $contratos = Contrato::where('contrato_status_id', ContratoStatus::ATIVO)
            ->get();

        $itens = collect();

        foreach($contratos as $contrato) {
            foreach($contrato->itens as $item) {
                if((starts_with($item->insumo->nome, 'MATERIAL'))) {
                    $itens[$item->id] = [
                        'obra' => $contrato->obra->nome,
                        'codigo' => $item->insumo->codigo,
                        'insumo' => $item->insumo->nome,
                        'unidade_medida' => $item->insumo->unidade_sigla,
                        'insumo_id' => $item->insumo->id,
                        'obra_id' => $contrato->obra_id,
                        'contrato_id' => $contrato->id,
                        'insumo_grupo_id' => $item->insumo->insumo_grupo_id,
                    ];
                }
            }
        }
        
        $obras = Obra::whereIn('id', $itens->pluck('obra_id', 'obra_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $grupo_insumos = InsumoGrupo::whereIn('id', $itens->pluck('insumo_grupo_id', 'insumo_grupo_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $insumos = Insumo::whereIn('id', $itens->pluck('insumo_id', 'insumo_id')->toArray())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        if($request->obra_id) {
            $itens = $itens->where('obra_id', $request->obra_id);
        }

        if($request->insumo_grupo_id) {
            $itens = $itens->where('insumo_grupo_id', $request->insumo_grupo_id);
        }
        
        if($request->insumo_id) {
            $itens = $itens->where('insumo_id', $request->insumo_id);
        }

        return view('gestao_estoque.estoque_minimo', compact('obras', 'insumos', 'itens', 'grupo_insumos'));
    }
}
