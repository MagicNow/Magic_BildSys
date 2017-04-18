<?php

namespace App\Http\Controllers;

use App\Models\MegaInsumo;
use App\Repositories\ImportacaoRepository;
use Illuminate\Http\Request;
use App\Models\OrdemDeCompra;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reprovados = OrdemDeCompra::select([
            'ordem_de_compras.id',
            'obras.nome',
            'users.name'
        ])            ->join('obras','obras.id','ordem_de_compras.obra_id')
            ->join('users', 'users.id','=', 'ordem_de_compras.user_id')
            ->where('oc_status_id', 4)->orderBy('id', 'desc')
            ->take(5)->get();

        $aprovados = OrdemDeCompra::select([
                'ordem_de_compras.id',
                'obras.nome',
                'users.name'
            ])            ->join('obras','obras.id','ordem_de_compras.obra_id')
                ->join('users', 'users.id','=', 'ordem_de_compras.user_id')
                ->where('oc_status_id', 5)->orderBy('id', 'desc')
                ->take(5)->get();

        $emaprovacao = OrdemDeCompra::select([
                'ordem_de_compras.id',
                'obras.nome',
                'users.name'
            ])            ->join('obras','obras.id','ordem_de_compras.obra_id')
                ->join('users', 'users.id','=', 'ordem_de_compras.user_id')
                ->where('oc_status_id', 3)->orderBy('id', 'desc')
                ->take(5)->get();

        return view('home',compact('reprovados', 'aprovados', 'emaprovacao'));
    }
}
