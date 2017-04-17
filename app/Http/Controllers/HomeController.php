<?php

namespace App\Http\Controllers;

use App\Models\MegaInsumo;
use App\Repositories\ImportacaoRepository;
use Illuminate\Http\Request;

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
        ImportacaoRepository::insumos();
        return view('home');
    }
}
