<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\InsumoGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarController extends AppBaseController
{
    public function getInsumoGrupos(Request $request)
    {
        $insumo_grupos = InsumoGrupo::select([
            'id',
            'nome'
        ])
        ->where('nome', 'like', '%'.$request->q.'%')->paginate();

        return $insumo_grupos;
    }

    public function getInsumos(Request $request)
    {
        $insumos = Insumo::select([
            'id',
            DB::raw("CONCAT(nome, ' - ', unidade_sigla) as nome")
        ])
        ->where(function ($query) use ($request) {
            $query->where('nome', 'like', '%' . $request->q . '%')
                ->orWhere('unidade_sigla', 'like', '%'.$request->q.'%');
        })
        ->where('active', 1)
        ->whereHas('grupo', function ($query) {
            return $query->where('active', 1);
        })
        ->orderBy('nome', 'ASC');

        if ($request->insumos_trocados) {
            $insumos = $insumos->whereNotIn('id', explode(',', $request->insumos_trocados));
        }

        $insumos = $insumos->paginate();

        return $insumos;
    }
}
