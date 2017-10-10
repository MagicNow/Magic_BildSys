<?php

namespace App\Http\Controllers;

class GestaoEstoqueController extends AppBaseController
{

    public function index()
    {
        return view('gestao_estoque.index');
    }
}
