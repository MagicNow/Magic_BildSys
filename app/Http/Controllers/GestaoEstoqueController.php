<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\ContratoStatus;
use App\Models\Estoque;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\Obra;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

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
            ->with('obra', 'insumo');

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

        $estoque = $estoque->paginate(10);
        return view('gestao_estoque.index', compact('estoque', 'obras', 'insumos', 'grupo_insumos'));
    }

    public function estoqueMinimo(Request $request)
    {
        $contratos = Contrato::where('contrato_status_id', ContratoStatus::ATIVO)
            ->get();

        $itens = collect();

        foreach($contratos as $contrato) {
            foreach($contrato->itens as $item) {
                if((starts_with($item->insumo->insumoGrupo->nome, 'MATERIAL'))) {
                    $itens[$contrato->obra_id.$item->insumo->id] = [
                        'obra' => $contrato->obra->nome,
                        'codigo' => $item->insumo->codigo,
                        'insumo' => $item->insumo->nome,
                        'unidade_medida' => $item->insumo->unidade_sigla,
                        'insumo_id' => $item->insumo->id,
                        'obra_id' => $contrato->obra_id,
                        'contrato_id' => $contrato->id,
                        'insumo_grupo_id' => $item->insumo->insumo_grupo_id,
                        'controlado' => $item->insumo->controlado,
                        'qtd_minima' => $item->insumo->qtd_minima,
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

        $itens = $itens->sortByDesc('obra_id');

        $page = Input::get('page', 1); // Get the ?page=1 from the url
        $perPage = 10; // Number of items per page
        $offset = ($page * $perPage) - $perPage;

        $itens = new LengthAwarePaginator(
            array_slice($itens->toArray(), $offset, $perPage, true), // Only grab the items we need
            count($itens), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );

        return view('gestao_estoque.estoque_minimo', compact('obras', 'insumos', 'itens', 'grupo_insumos'));
    }

    public function estoqueMinimoSalvar(Request $request)
    {

    }
}
