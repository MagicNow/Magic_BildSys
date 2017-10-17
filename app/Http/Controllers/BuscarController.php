<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\QcAvulsoCarteira;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fornecedor;
use App\Models\TipoEqualizacaoTecnica;

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
            DB::raw("CONCAT(codigo, ' - ', nome, ' - ', unidade_sigla) as nome")
        ])
        ->where(function ($query) use ($request) {
            $query->where('codigo', 'like', '%' . $request->q . '%')
                ->orWhere('nome', 'like', '%' . $request->q . '%')
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

    public function getFornecedores(Request $request)
    {
        $fornecedores = Fornecedor::select([
            'id',
            'nome',
            'cnpj'
        ])
            ->where(function ($subquery) use ($request){
                $subquery->where('nome', 'like', '%'.$request->q.'%');
                $subquery->orWhere('cnpj', 'like', '%'.$request->q.'%');
            })
        ->whereNotIn('id', $request->ignore ?: [])
        ->paginate();

        return $fornecedores;
    }

    public function getCarteiras(Request $request)
    {
        $carteiras = Carteira::select([
            'id',
            'nome'
        ])
        ->where('nome', 'like', '%'.$request->q.'%')
        ->whereNotIn('id', $request->ignore ?: [])
        ->paginate();

        return $carteiras;
    }

    public function getQcAvulsoCarteiras(Request $request)
    {
        $carteiras = QcAvulsoCarteira::select([
            'id',
            'nome'
        ])
        ->where('nome', 'like', '%'.$request->q.'%')
        ->whereNotIn('id', $request->ignore ?: [])
        ->with('tarefas')
        ->paginate();

        return $carteiras;
    }

	public function getTipoEqualizacaoTecnicas(Request $request)
    {
        $tipo_equalizacao_tecnicas = TipoEqualizacaoTecnica::select([
            'id',
            'nome as name'
        ])
        ->where('nome', 'like', '%'.$request->q.'%')->paginate();

        return $tipo_equalizacao_tecnicas;
    }
}
