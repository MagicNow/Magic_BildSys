<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Insumo;
use App\Models\Obra;
use Illuminate\Http\Request;

class GestaoEstoqueController extends AppBaseController
{

    public function index(Request $request)
    {

        $estoque = Estoque::with('obra', 'insumo')->get();

        $obras = Obra::whereIn('id', $estoque->pluck('obra_id', 'obra_id')->toArray())
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

        if($request->insumo_id) {
            $estoque = $estoque->where('insumo_id', $request->insumo_id);
        }

        return view('gestao_estoque.index', compact('estoque', 'obras', 'insumos'));
    }
}
