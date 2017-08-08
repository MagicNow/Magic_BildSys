<?php

namespace App\Http\Controllers;

use App\Models\MegaInsumo;
use App\Models\WorkflowAlcada;
use App\Repositories\ImportacaoRepository;
use Illuminate\Http\Request;
use App\Models\OrdemDeCompra;
use App\Repositories\QuadroDeConcorrenciaRepository;


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
    public function index(QuadroDeConcorrenciaRepository $quadroDeConcorrenciaRepository)
    {
        $workflow_prazos = [];
        $workflow_alcadas_user = WorkflowAlcada::whereHas('workflowUsuarios', function($query){
                $query->where('user_id',auth()->id());
            })->get();
        if($workflow_alcadas_user->count()){
            foreach ($workflow_alcadas_user as $alcada){
                $workflow_prazos[$alcada->workflow_tipo_id] = $alcada->dias_prazo;
            }
        }
        
        $quadros = $quadroDeConcorrenciaRepository
            ->quadrosPreenchiveisPeloUsuario(auth()->user())
            ->count();
        return view('home', compact('quadros','workflow_prazos'));
    }
}
