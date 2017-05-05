<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PlanejamentoOrcamentoDataTable;
use App\Http\Requests\Admin;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\Planejamento;
use App\Models\PlanejamentoCompra;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class PlanejamentoOrcamentoController extends AppBaseController
{

    /**
     * Display a listing of the PlanejamentoOrcamento.
     *
     * @param PlanejamentoOrcamentoDataTable $planejamentoOrcamentoDataTable
     * @return Response
     */
    public function index()
    {
        $planejamentos = Planejamento::pluck('tarefa','id')->toArray();
        $obras = Obra::pluck('nome','id')->toArray();
        return view('admin.planejamento_orcamentos.index', compact('planejamentos','obras'));
    }

    /**
     * Show the form for creating a new PlanejamentoOrcamento.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.planejamento_orcamentos.create');
    }

    /**
     * Store a newly created PlanejamentoOrcamento in storage.
     *
     * @param CreatePlanejamentoOrcamentoRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        dd($request->all());

        Flash::success('Planejamento Orcamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.planejamentoOrcamentos.index'));
    }

    /**
     * Display the specified PlanejamentoOrcamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return view('admin.planejamento_orcamentos.show', compact('id'));
    }

    /**
     * Show the form for editing the specified PlanejamentoOrcamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.planejamento_orcamentos.edit', compact('id'));
    }

    /**
     * Update the specified PlanejamentoOrcamento in storage.
     *
     * @param  int              $id
     * @param UpdatePlanejamentoOrcamentoRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {

        Flash::success('Planejamento Orcamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.planejamentoOrcamentos.index'));
    }

    public function GrupoRelacionados(Request $request){
        $proximo = '';
        $retorno = collect([]);
        switch($request->tipo){
            case 'subgrupo1_id' :
                $proximo = 'subgrupo2_id';
                break;
            case 'subgrupo2_id' :
                $proximo = 'subgrupo3_id';
                break;
            case 'subgrupo3_id' :
                $proximo = 'servico_id';
                break;
            case 'servico_id' :
                $proximo = 'insumo_id';
                break;
        }
        if($request->tipo == 'subgrupo1_id' || $request->tipo == 'subgrupo2_id' || $request->tipo == 'subgrupo3_id') {
            #grupos
            $retorno = Orcamento::select(['orcamentos.' . $request->tipo.' as id', 'grupos.codigo', 'grupos.nome',DB::raw("'".$request->tipo."'  as atual"),DB::raw("'".$proximo."'  as proximo")])
                ->join('grupos', 'grupos.id', '=', 'orcamentos.' . $request->tipo)
                ->where('orcamentos.' . $request->campo, $request->id)
                ->groupBy('orcamentos.' . $request->tipo, 'grupos.codigo', 'grupos.nome')
                ->get();
        }elseif($request->tipo == 'servico_id'){
            #serviços
            $retorno = Orcamento::select(['orcamentos.' . $request->tipo.' as id', 'servicos.codigo', 'servicos.nome',DB::raw("'".$request->tipo."'  as atual"),DB::raw("'".$proximo."'  as proximo")])
                ->join('servicos', 'servicos.id', '=', 'orcamentos.servico_id')
                ->where('orcamentos.' . $request->campo, $request->id)
                ->groupBy('orcamentos.' . $request->tipo, 'servicos.codigo', 'servicos.nome')
                ->get();
        }else{
            #insumos
            $retorno = Orcamento::select(['orcamentos.id', 'insumos.codigo', 'insumos.nome',DB::raw("'".$request->tipo."'  as atual")])
                ->join('insumos', 'insumos.id', '=', 'orcamentos.insumo_id')
                ->where('orcamentos.' . $request->campo, $request->id)
                ->groupBy('orcamentos.id', 'insumos.codigo', 'insumos.nome')
                ->get();
        }
        return $retorno;
    }

    public function getPlanejamentos($id){
        $planejamentos = Planejamento::where('obra_id', $id)
            ->pluck('tarefa','id')->toArray();
        return $planejamentos;
    }

    public function getOrcamentos($id){
        $orcamentos = Orcamento::select(['orcamentos.obra_id','orcamentos.grupo_id','grupos.codigo', 'grupos.nome'])
            ->join('grupos','grupos.id','=','orcamentos.grupo_id')
            ->where('orcamentos.obra_id', $id)
            ->groupBy('grupo_id','obra_id','grupos.codigo', 'grupos.nome')
            ->first();
        return $orcamentos;
    }

    public function planejamentoCompras(Request $request){
        $insumosOrcados = null;
        if($request->insumos){
            $insumosOrcados = Orcamento::whereIn('insumo_id', $request->insumos)
                ->where('obra_id', $request->grupo_id)
                ->where('ativo', 1)
                ->get();
        }
        if($request->servico_id){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('subgrupo2_id', $request->subgrupo2_id)
                ->where('subgrupo3_id', $request->subgrupo3_id)
                ->where('servico_id', $request->servico_id)
                ->where('ativo', 1)
                ->get();
        }
        if($request->grupo_id && $request->subgrupo1_id && $request->subgrupo2_id && $request->subgrupo3_id && !$request->servico_id && !$request->insumos){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('subgrupo2_id', $request->subgrupo2_id)
                ->where('subgrupo3_id', $request->subgrupo3_id)
                ->where('ativo', 1)
                ->get();
        }
        if($request->grupo_id && $request->subgrupo1_id && $request->subgrupo2_id && !$request->subgrupo3_id && !$request->servico_id && !$request->insumos){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('subgrupo2_id', $request->subgrupo2_id)
                ->where('ativo', 1)
                ->get();
        }
        if($request->grupo_id && $request->subgrupo1_id && !$request->subgrupo2_id && !$request->subgrupo3_id && !$request->servico_id && !$request->insumos){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('ativo', 1)
                ->get();
        }
        if($request->grupo_id && !$request->subgrupo1_id && !$request->subgrupo2_id && !$request->subgrupo3_id && !$request->servico_id && !$request->insumos){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)
                ->where('ativo', 1)
                ->get();
        }

        if(count($insumosOrcados)) {
            foreach ($insumosOrcados as $insumosOrcado) {
                $cadastrado = PlanejamentoCompra::where('insumo_id',$insumosOrcado->insumo_id)
                    ->where('planejamento_id', $request->planejamento_id)
                    ->first();
                if(!$cadastrado) {
                    $planejamentoCompra = new PlanejamentoCompra();
                    $planejamentoCompra->planejamento_id = $request->planejamento_id;
                    $planejamentoCompra->insumo_id = $insumosOrcado->insumo_id;
                    $planejamentoCompra->codigo_estruturado = $insumosOrcado->codigo_insumo;
                    $planejamentoCompra->grupo_id = $insumosOrcado->grupo_id;
                    $planejamentoCompra->subgrupo1_id = $insumosOrcado->subgrupo1_id;
                    $planejamentoCompra->subgrupo2_id = $insumosOrcado->subgrupo2_id;
                    $planejamentoCompra->subgrupo3_id = $insumosOrcado->subgrupo3_id;
                    $planejamentoCompra->servico_id = $insumosOrcado->servico_id;
                    $planejamentoCompra->save();
                }
            }

            Flash::success('Planejamento de compras inseridos!');
            return redirect('/admin/planejamentos/atividade/'.$request->planejamento_id);
//            return Response()->json(['success' => true]);
        }
        Flash::error('Não foram encontrados insumos em orçamentos com os filtros passados!');
        return redirect('/admin/planejamentos/atividade/'.$request->planejamento_id.'/edit');
//        return Response()->json(['success' => false, 'msg' => 'Não foram encontrado insumo no orçamento com os filtros passados!']);
    }

}
