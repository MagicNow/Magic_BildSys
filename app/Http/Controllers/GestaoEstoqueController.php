<?php

namespace App\Http\Controllers;

use App\Models\Estoque;

class GestaoEstoqueController extends AppBaseController
{

    public function index()
    {
        $estoque = Estoque::with('obra', 'insumo')->get();

        return view('gestao_estoque.index', compact('estoque'));
    }
}
