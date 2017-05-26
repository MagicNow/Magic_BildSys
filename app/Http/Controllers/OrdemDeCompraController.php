<?php

namespace App\Http\Controllers;

use Exception;
use App\DataTables\ComprasDataTable;
use App\DataTables\InsumosAprovadosDataTable;
use App\DataTables\LembretesHomeDataTable;
use App\DataTables\OrdemDeCompraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateOrdemDeCompraRequest;
use App\Http\Requests\UpdateOrdemDeCompraRequest;
use App\Models\Cidade;
use App\Models\ContratoInsumo;
use App\Models\Insumo;
use App\Models\Grupo;
use App\Models\InsumoGrupo;
use App\Models\InsumoServico;
use App\Models\Lembrete;
use App\Models\ObraUser;
use App\Models\OrdemDeCompraItemAnexo;
use App\Models\OrdemDeCompraStatusLog;
use App\Models\Planejamento;
use App\Models\PlanejamentoCompra;
use App\Models\Servico;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowReprovacaoMotivo;
use App\Repositories\CodeRepository;
use function foo\func;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\OrdemDeCompraItem;

use App\Models\OrdemDeCompra;

use App\Repositories\OrdemDeCompraRepository;
use App\Repositories\OrdemDeCompraItemRepository;
use App\Repositories\WorkflowAprovacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\Admin\InsumoGrupoRepository;
use App\Repositories\Admin\PlanejamentoRepository;
use App\Repositories\Admin\OrcamentoRepository;
use App\Repositories\Admin\InsumoRepository;

class OrdemDeCompraController extends AppBaseController
{
    /** @var  OrdemDeCompraRepository */
    private $ordemDeCompraRepository;

    public function __construct(OrdemDeCompraRepository $ordemDeCompraRepo)
    {
        $this->ordemDeCompraRepository = $ordemDeCompraRepo;
    }

    /**
     * Display a listing of the OrdemDeCompra.
     *
     * @param OrdemDeCompraDataTable $ordemDeCompraDataTable
     * @return Response
     */
    public function index(OrdemDeCompraDataTable $ordemDeCompraDataTable)
    {
        return $ordemDeCompraDataTable->render('ordem_de_compras.index');
    }

    /**
     * Show the form for creating a new OrdemDeCompra.
     *
     * @return Response
     */
    public function create()
    {
        return view('ordem_de_compras.create');
    }

    /**
     * Store a newly created OrdemDeCompra in storage.
     *
     * @param CreateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function store(CreateOrdemDeCompraRequest $request)
    {
        $input = $request->all();

        $ordemDeCompra = $this->ordemDeCompraRepository->create($input);

        Flash::success('Ordem De Compra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect('/ordens-de-compra');
    }

    /**
     * Display the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect('/ordens-de-compra');
        }

        return view('ordem_de_compras.show')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Show the form for editing the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect('/ordens-de-compra');
        }

        return view('ordem_de_compras.edit')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Update the specified OrdemDeCompra in storage.
     *
     * @param  int              $id
     * @param UpdateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrdemDeCompraRequest $request)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect('/ordens-de-compra');
        }

        $ordemDeCompra = $this->ordemDeCompraRepository->update($request->all(), $id);

        Flash::success('Ordem De Compra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect('/ordens-de-compra');
    }

    /**
     * Remove the specified OrdemDeCompra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect('/ordens-de-compra');
        }

        $this->ordemDeCompraRepository->delete($id);

        Flash::success('Ordem De Compra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect('/ordens-de-compra');
    }

    public function compras(
        Request $request,
        LembretesHomeDataTable $lembretesHomeDataTable,
        ObraRepository $obraRepository,
        InsumoGrupoRepository $insumoGrupoRepository,
        PlanejamentoRepository $planejamentoRepository
    ) {
        $obras = $obraRepository
            ->findByUser($request->user()->id)
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $grupos = $insumoGrupoRepository
            ->comLembretesComItensDeCompraPorUsuario($request->user()->id)
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $atividades = $planejamentoRepository
            ->comLembretesComItensDeCompraPorUsuario($request->user()->id)
            ->prepend('', '')
            ->pluck('tarefa', 'id')
            ->toArray();

        return $lembretesHomeDataTable->render(
            'ordem_de_compras.compras',
            compact('obras', 'grupos', 'atividades')
        );
    }

    /**
     * Exibe os detalhes da Ordem de Compra
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detalhe($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return back();
        }

        $orcamentoInicial = $totalAGastar = $realizado = 0;

        $itens = collect([]);
        $avaliado_reprovado = [];
        $itens_ids = $ordemDeCompra->itens()->pluck('id', 'id')->toArray();
        $aprovavelTudo = WorkflowAprovacaoRepository::verificaAprovaGrupo('OrdemDeCompraItem', $itens_ids, Auth::user());
        $alcadas = WorkflowAlcada::where('workflow_tipo_id', 1)->orderBy('ordem','ASC')->get(); // Aprovação de OC

        if($ordemDeCompra->oc_status_id == 3) { //Em Aprovação
            foreach ($alcadas as $alcada) {
                $avaliado_reprovado[$alcada->id] = WorkflowAprovacaoRepository::verificaTotalJaAprovadoReprovado(
                    'OrdemDeCompraItem',
                    $ordemDeCompra->itens()->pluck('id', 'id')->toArray(),
                    null,
                    null,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['aprovadores'] = WorkflowAprovacaoRepository::verificaQuantidadeUsuariosAprovadores(
                    1, // Aprovação de OC
                    $ordemDeCompra->obra_id,
                    $alcada->id);

                $avaliado_reprovado[$alcada->id] ['faltam_aprovar'] = WorkflowAprovacaoRepository::verificaUsuariosQueFaltamAprovar(
                    'OrdemDeCompraItem',
                    1, // Aprovação de OC
                    $ordemDeCompra->obra_id,
                    $alcada->id,
                    $itens_ids);

                // Data do início da  Alçada
                if($alcada->ordem===1){
                    $ordem_status_log = $ordemDeCompra->ordemDeCompraStatusLogs()
                        ->where('oc_status_id', 2)->first();
                    if($ordem_status_log){
                        $avaliado_reprovado[$alcada->id] ['data_inicio'] = $ordem_status_log->created_at
                            ->format('d/m/Y H:i');
                    }
                }else{
                    $primeiro_voto = WorkflowAprovacao::where('aprovavel_type', 'App\\Models\\OrdemDeCompraItem')
                        ->whereIn('aprovavel_id', $itens_ids)
                        ->where('workflow_alcada_id',$alcada->id)
                        ->orderBy('id','ASC')
                        ->first();
                    if($primeiro_voto){
                        $avaliado_reprovado[$alcada->id]['data_inicio'] = $primeiro_voto->created_at->format('d/m/Y H:i');
                    }
                }
            }
        }

        if($ordemDeCompra->itens){
            $orcamentoInicial = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
                ->join('orcamentos', function ($join) use ($ordemDeCompra) {
                    $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                    $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                    $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                    $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                    $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                    $join->on('orcamentos.servico_id','=', 'ordem_de_compra_itens.servico_id');
                    $join->on('orcamentos.obra_id','=', DB::raw($ordemDeCompra->obra_id));
                    $join->on('orcamentos.ativo','=', DB::raw('1'));
                })
                ->sum('orcamentos.preco_total');

            $totalAGastar = $ordemDeCompra->itens()->sum('valor_total');

            $realizado = OrdemDeCompraItem::join('ordem_de_compras','ordem_de_compras.id','=','ordem_de_compra_itens.ordem_de_compra_id')
                ->where('ordem_de_compras.obra_id',$ordemDeCompra->obra_id)
                ->whereIn('oc_status_id',[2,3,5])
                ->whereIn('ordem_de_compra_itens.insumo_id',$ordemDeCompra->itens()->pluck('insumo_id','insumo_id')->toArray())
                ->sum('ordem_de_compra_itens.valor_total');

            $saldo = $orcamentoInicial - $realizado;

            $itens = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
                ->select([
                    'ordem_de_compra_itens.*',
                    DB::raw("(SELECT SUM( qtd )
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ordem_de_compra_itens.servico_id
                    AND OCI2.obra_id = ".$ordemDeCompra->obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as qtd_realizada"),
                    DB::raw("(SELECT SUM( valor_total )
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ordem_de_compra_itens.servico_id
                    AND OCI2.obra_id = ".$ordemDeCompra->obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as valor_realizado"),
                    'orcamentos.qtd_total as qtd_inicial',
                    'orcamentos.preco_total as preco_inicial',
                ])
                ->join('orcamentos', function ($join) use ($ordemDeCompra){
                    $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                    $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                    $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                    $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                    $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                    $join->on('orcamentos.servico_id','=', 'ordem_de_compra_itens.servico_id');
                    $join->on('orcamentos.obra_id','=', DB::raw($ordemDeCompra->obra_id));
                    $join->on('orcamentos.ativo','=', DB::raw('1'));
                })
                ->with('insumo','unidade','anexos')
                ->paginate(10);
        }

        $motivos_reprovacao = WorkflowReprovacaoMotivo::where(function($query){
            $query->where('workflow_tipo_id',1);
            $query->orWhereNull('workflow_tipo_id');
        })->pluck('nome','id')->toArray();

        $oc_status = $ordemDeCompra->ocStatus->nome;

        $qtd_itens = $ordemDeCompra->itens()->count();

        $alcadas_count = $alcadas->count();

        return view('ordem_de_compras.detalhe', compact(
            'ordemDeCompra',
            'orcamentoInicial',
            'realizado',
            'totalAGastar',
            'saldo',
            'itens',
            'motivos_reprovacao',
            'aprovavelTudo',
            'avaliado_reprovado',
            'qtd_itens',
            'oc_status',
            'alcadas_count'
        )
    );
    }

    /**
     * Tela que traz a lista de insumos.
     *
     * @param  Planejamento $planejamento
     * @param  InsumoGrupo $insumoGrupo
     * @return Render View
     */
    public function insumos(Request $request){
        $planejamento = Planejamento::find($request->planejamento_id);
        if(isset($request->obra_id)){
            $obra = Obra::find($request->obra_id);
            return view('ordem_de_compras.insumos', compact('planejamento', 'obra'));
        }

        return view('ordem_de_compras.insumos', compact('planejamento'));
    }

    /**
     * Carrega filtros dos insumos.
     *
     * @return Response Json
     */
    public function insumosFilters(){
        $filters = OrdemDeCompra::$filters_insumos;
        return response()->json($filters);
    }

    /**
     * Tela que traz a lista de insumos.
     *
     * @param  Request $request
     * @param  Planejamento $planejamento
     * @return Response  Json
     */
    public function insumosJson(Request $request)
    {
        $planejamento = Planejamento::find($request->planejamento_id);

        //Query para utilização dos filtros
        $insumo_query = Insumo::query();
        $insumos = $insumo_query->join('insumo_servico', 'insumo_servico.insumo_id','=','insumos.id')
            ->join('servicos','servicos.id','=','insumo_servico.servico_id')
            ->join('orcamentos','orcamentos.insumo_id', '=', 'insumos.id')
            ->select([
                'insumos.id',
                'insumos.codigo as insumo_cod',
                'insumos.unidade_sigla',
                'insumos.nome as descricao',
                'servicos.id as servico_id',
                'servicos.nome as servico',
                'servicos.codigo as cod_servico',
                'servicos.grupo_id as cod_grupo',
                'orcamentos.codigo_insumo as cod_estruturado',
                'orcamentos.subgrupo1_id as cod_subgrupo1',
                'orcamentos.subgrupo2_id as cod_subgrupo2',
                'orcamentos.subgrupo3_id as cod_subgrupo3',
                DB::raw('(SELECT count(id) FROM planejamento_compras
                WHERE planejamento_compras.insumo_id = insumos.id
                AND planejamento_compras.planejamento_id ='.$planejamento->id.' AND planejamento_compras.deleted_at IS NULL) as adicionado')
            ]);

        if(isset($request->orderkey)){
            $insumos->orderBy($request->orderkey, $request->order);
        }

        //Aplica filtro do Jhonatan
        $insumos = CodeRepository::filter($insumos, $request->all());
        return response()->json($insumos->paginate(10), 200);
    }

    /**
     * Adiciona insumo a lista de obras insumo.
     *
     * @param  Request $request
     * @param  Planejamento $planejamento
     * @return Response  Json
     */
    public function insumosAdd(Request $request)
    {
        $planejamento = Planejamento::find($request->planejamento_id);
        try{
            $planejamento_compras = new PlanejamentoCompra();
            $planejamento_compras->planejamento_id = $planejamento->id;
            $planejamento_compras->insumo_id = $request->id;
            $planejamento_compras->codigo_estruturado = $request->cod_estruturado;
            $planejamento_compras->grupo_id = $request->cod_grupo;
            $planejamento_compras->subgrupo1_id = $request->cod_subgrupo1;
            $planejamento_compras->subgrupo2_id = $request->cod_subgrupo2;
            $planejamento_compras->subgrupo3_id = $request->cod_subgrupo3;
            $planejamento_compras->servico_id = $request->servico_id;
            $salvo = $planejamento_compras->save();

            Flash::success('Insumo adicionado com sucesso');
            return response()->json(['success'=>$salvo]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Tela que traz os insumos de uma tarefa especifica de uma obra.
     *
     * @param  Request $request
     * @param  Planejamento $planejamento
     * @param  InsumoGrupo $insumoGrupo
     * @return Render View
     */
    public function obrasInsumos(
        ComprasDataTable $comprasDataTable,
        Request $request,
        InsumoGrupoRepository $insumoGrupoRepository,
        PlanejamentoRepository $planejamentoRepository
    ) {
        $planejamento = Planejamento::find($request->planejamento_id);
        $insumoGrupo = InsumoGrupo::find($request->insumo_grupos_id);

        $obra = Obra::find($request->obra_id);

        $grupos = Grupo::whereNull('grupo_id')
            ->select([
                'id',
                DB::raw("CONCAT(codigo, ' ', nome) as nome")
            ])
            ->pluck('nome','id')
            ->toArray();

        $insumoGrupos = $insumoGrupoRepository
            ->comLembretesComItensDeCompraPorUsuario($request->user()->id)
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $planejamentos = $planejamentoRepository
            ->comLembretesComItensDeCompraPorUsuario($request->user()->id)
            ->prepend('', '')
            ->pluck('tarefa', 'id')
            ->toArray();

        return $comprasDataTable->render(
            'ordem_de_compras.obras_insumos',
            compact(
                'obra',
                'grupos',
                'planejamento',
                'insumoGrupo',
                'insumoGrupos',
                'planejamentos'
            )
        );
    }

    public function removerInsumoPlanejamento(PlanejamentoCompra $planejamentoCompra)
    {
        PlanejamentoCompra::destroy($planejamentoCompra->id);
        return response()->redirect()->back();
    }

    public function addCarrinho(Request $request)
    {
        //Testa se tem ordem de compra aberta pro user

        $ordem = null;
        if(\Session::get('ordemCompra')){
            $ordem = OrdemDeCompra::where('id', \Session::get('ordemCompra'))
                ->where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request->obra_id)->first();
        }else {
            $ordem = OrdemDeCompra::where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request->obra_id)->first();
        }

        if(!$ordem){
            $ordem = new OrdemDeCompra();
            $ordem->oc_status_id = 1;
            $ordem->obra_id = $request->obra_id;
            $ordem->user_id = Auth::user()->id;
            $ordem->save();
            OrdemDeCompraStatusLog::create([
                'oc_status_id'=>1,
                'ordem_de_compra_id'=>$ordem->id,
                'user_id'=>Auth::id()
            ]);

            # Colocando na sessão
            $request->session()->put('ordemCompra', $ordem->id);
        }

        // Encontra o orçamento ativo para validar preço
        $orcamento_ativo = Orcamento::where('insumo_id',$request->id)
            ->where('obra_id',$request->obra_id)
            ->where('grupo_id',$request->grupo_id)
            ->where('subgrupo1_id',$request->subgrupo1_id)
            ->where('subgrupo2_id',$request->subgrupo2_id)
            ->where('subgrupo3_id',$request->subgrupo3_id)
            ->where('servico_id',$request->servico_id)
            ->where('ativo',1)
            ->first();

        if(!$orcamento_ativo){
            return response()->json(['success'=>false,'error'=>'Um item de orçamento ativo deste insumo não foi encontrado.']);
        }

        $ordem_item = OrdemDeCompraItem::firstOrNew([
            'ordem_de_compra_id' => $ordem->id,
            'obra_id' => $request->obra_id,
            'codigo_insumo' => $orcamento_ativo->codigo_insumo,
            'grupo_id' => $orcamento_ativo->grupo_id,
            'subgrupo1_id' => $orcamento_ativo->subgrupo1_id,
            'subgrupo2_id' => $orcamento_ativo->subgrupo2_id,
            'subgrupo3_id' => $orcamento_ativo->subgrupo3_id,
            'servico_id' => $orcamento_ativo->servico_id,
            'insumo_id' => $orcamento_ativo->insumo_id,
            'unidade_sigla' => $orcamento_ativo->unidade_sigla,
        ]);

        $ordem_item->user_id = Auth::user()->id;
        $ordem_item->qtd = $request->quantidade_compra;
        $ordem_item->valor_unitario = $orcamento_ativo->preco_unitario;
        $ordem_item->valor_total = $orcamento_ativo->getOriginal('preco_unitario') * money_to_float($request->quantidade_compra);

        $insumo = Insumo::find($orcamento_ativo->insumo_id);

        $tems = \DB::connection('oracle')->select('(
                       Select p.pro_tab_in_codigo,
                           p.pro_pad_in_codigo,
                           p.pro_in_codigo,
                           p.pro_st_descricao,
                           p.uni_st_unidade,
                           p.gru_in_codigo,
                           grp.gru_st_nome,
                           dp.pro_st_dettecnico
                       From mgadm.est_produtos      p,
                            mgadm.est_detprodutos  dp,
                            mgadm.est_grupos      grp      
                       Where dp.pro_tab_in_codigo = p.pro_tab_in_codigo
                       And   dp.pro_pad_in_codigo = p.pro_pad_in_codigo
                       And   dp.pro_in_codigo     = p.pro_in_codigo
                      
                       And   p.gru_tab_in_codigo  = grp.gru_tab_in_codigo
                       And   p.gru_pad_in_codigo  = grp.gru_pad_in_codigo
                       And   p.gru_ide_st_codigo  = grp.gru_ide_st_codigo
                       And   p.gru_in_codigo      = grp.gru_in_codigo
                      
                       And   grp.gru_ide_st_codigo = 07
                           
                       And exists (Select 1
                                       From mgadm.est_detprodutos dp
                                       Where dp.pro_tab_in_codigo = p.pro_tab_in_codigo
                                       And   dp.pro_pad_in_codigo = p.pro_pad_in_codigo
                                       And   dp.pro_in_codigo     = p.pro_in_codigo)
                       And   p.pro_in_codigo = '.$insumo->codigo.'
                   )');

        $todos_tems = '';

        if(count($tems)){
            foreach ($tems as $tem){
                $todos_tems .= $tem->pro_st_dettecnico;
            }
        }
        $ordem_item->tems = $todos_tems;
        $salvo = $ordem_item->save();

        if(!$request->quantidade_compra || $request->quantidade_compra == '0' || $request->quantidade_compra == ''){
            $ordem_item->forceDelete();
        }

        return response()->json(['success'=>$salvo]);

    }


    /**
     * Tela que traz as opcoes de troca de insumos.
     *
     * @param  Insumo $insumo
     * @param  Planejamento $planejamento
     * @param  InsumoGrupo $insumoGrupo
     * @return Render View
     */
    public function trocaInsumos($id)
    {
        $insumo = Insumo::find($id);
        $planejamento = Planejamento::find(1);
        return view('ordem_de_compras.troca_insumos', compact('insumo','planejamento'));
    }

    /**
     * Método que retorna a lista de filtros aplicaveis a  troca insumos.
     *
     *
     * @return Json
     */
    public function trocaInsumosFilters(){
        $filters = OrdemDeCompra::$filters_obras_insumos;
        return response()->json($filters);
    }

    public function trocaInsumoAction(Request $request)
    {
        $planejamento = Planejamento::find($request->planejamento_id);
        $insumo = Insumo::find($request->insumo_pai);
        try{
            //            $planejamento_pai = PlanejamentoCompra::where('insumo_id', $insumo->id)->where('planejamento_id',$planejamento->id)->first();
            $planejamento_compras = new PlanejamentoCompra();
            $planejamento_compras->planejamento_id = $planejamento->id;
            $planejamento_compras->insumo_id = $request->id;
            $planejamento_compras->codigo_estruturado = $request->cod_estruturado;
            $planejamento_compras->grupo_id = $request->cod_grupo;
            $planejamento_compras->subgrupo1_id = $request->cod_subgrupo1;
            $planejamento_compras->subgrupo2_id = $request->cod_subgrupo2;
            $planejamento_compras->subgrupo3_id = $request->cod_subgrupo3;
            $planejamento_compras->servico_id = $request->servico_id;
            $planejamento_compras->insumo_pai = $insumo->id;
            $salvo = $planejamento_compras->save();
            Flash::success('Insumo adicionado com sucesso');
            return response()->json(['success'=>$salvo]);
        }catch (\Exception $e){
            Flash::error('Insumo adicionado com'. $e->getMessage());
            return response()->json('{response: "error'.$e->getMessage().'"}');
        }
    }

    public function trocaInsumosJsonFilho(Request $request){
        $planejamento = Planejamento::find($request->planejamento_id);
        $insumo = Insumo::find($request->insumo_pai);
        $insumo_query = Insumo::query();

        //Query pra trazer
        $insumos = $insumo_query->join('orcamentos','orcamentos.insumo_id','=','insumos.id')
            ->join('planejamento_compras','planejamento_compras.insumo_id','=','insumos.id')
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'orcamentos.grupo_id',
                'orcamentos.servico_id',
                'orcamentos.qtd_total',
                'orcamentos.preco_total'
            ])->where('deleted_at','=', null)
            ->where('planejamento_compras.insumo_pai',$insumo->id)
            ->where('planejamento_compras.planejamento_id', $planejamento->id)
            ->where('orcamentos.ativo',1);
        //            ->whereNotNull('planejamento_compras.trocado_de');
        return response()->json($insumos->paginate(10), 200);
    }

    public function trocaInsumosJsonPai(Insumo $insumo){

        $insumo = Insumo::where('id',$insumo->id);

        return response()->json($insumo->paginate(10), 200);
    }


    //Metodo de paginacao manual caso necessario
    protected function paginate($items, $perPage = 12){
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage, true);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($items),
            $perPage
        );
    }

    public function filterJsonOrdemCompra(){
        $filters = OrdemDeCompra::$filters;

        return response()->json($filters);
    }

    public function carrinho(Request $request)
    {
        $ordemDeCompra = OrdemDeCompra::where('oc_status_id',1)->where('user_id',Auth::id());
        if($request->obra_id){
            $ordemDeCompra->where('obra_id',$request->obra_id);
        }
        if($request->id){
            $ordemDeCompra->where('id',$request->id);
        }
        $ordemDeCompra = $ordemDeCompra->first();

        if (empty($ordemDeCompra)) {
            Flash::error('Não existe OC em aberto.');

            return back();
        }
        #colocar na sessão
        $request->session()->put('ordemCompra', $ordemDeCompra->id);

        $itens = collect([]);

        if($ordemDeCompra->itens){
            $itens = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
                ->with('insumo','unidade','anexos')
                ->paginate(10);
        }

        $obra_id = $ordemDeCompra->obra_id;

        return view('ordem_de_compras.carrinho', compact(
            'ordemDeCompra',
            'itens',
            'obra_id'
        )
    );
    }

    public function jsonOrdemCompraDashboard(Request $request){

        $ordem_compra = OrdemDeCompra::select([
            'ordem_de_compras.id',
            'obras.nome',
            'users.name'
        ])
        ->join('obras','obras.id','ordem_de_compras.obra_id')
        ->join('users', 'users.id','=', 'ordem_de_compras.user_id');

        if($request->type == 'created'){
            $ordem_compra->orderBy('id', 'desc')->take(5);
        }else{
            $ordem_compra->where('oc_status_id', $request->type)
                ->orderBy('id', 'desc')
                ->take(5);
        }

        return response()->json($ordem_compra->get(), 200);
    }

    public function fechaCarrinho(Request $request){
        $ordemDeCompra = OrdemDeCompra::where('oc_status_id',1)->where('user_id',Auth::id());
        if($request->obra_id){
            $ordemDeCompra->where('obra_id',$request->obra_id);
        }
        if($request->id){
            $ordemDeCompra->where('id',$request->id);
        }
        $ordemDeCompra = $ordemDeCompra->first();

        $ordem_itens = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
            ->where('obra_id', $ordemDeCompra->obra_id)
            ->get();

        foreach ($ordem_itens as $item){
            if(!$item->aprovado){ // Se o item não esta aprovado
                if($item->updated_at < $ordemDeCompra->updated_at){ // Se o item for atualizado  antes da ordem de compra
                    Flash::error('O item não foi atualizado.');
                    return back();
                }else{
                    $item->aprovado = null;
                    $item->update();
                }
            }
            if($item->qtd == '0.00' || !$item->qtd){
                Flash::error('A quantidade não pode ser zero.');
                return back();
            }
            if($item->valor_unitario == '0.00' || !$item->valor_unitario){
                Flash::error('O valor unitário não pode ser zero.');
                return back();
            }
            if($item->valor_total == '0.00' || !$item->valor_total){
                Flash::error('O valor total não pode ser zero.');
                return back();
            }
        }

        if (empty($ordemDeCompra)) {
            Flash::error('Não existe OC em aberto.');

            return back();
        }
        OrdemDeCompraStatusLog::create([
            'oc_status_id'=>2, // Fechado
            'ordem_de_compra_id'=>$ordemDeCompra->id,
            'user_id'=>Auth::id()
        ]);
        $ordemDeCompra->oc_status_id = 3; // Em Aprovação
        $ordemDeCompra->save();
        OrdemDeCompraStatusLog::create([
            'oc_status_id'=>$ordemDeCompra->oc_status_id,
            'ordem_de_compra_id'=>$ordemDeCompra->id,
            'user_id'=>Auth::id()
        ]);
        // Já muda para Em Aprovação

        // Agora altera todos os Planejamentos compra que estão ligadas à essa zerando a quantidade do pré-carrinho
        $planejamento_compras_zerar = $ordemDeCompra->itens()
            ->join('planejamento_compras',function($join){
                $join->on('planejamento_compras.insumo_id','=','ordem_de_compra_itens.insumo_id');
                $join->on('planejamento_compras.servico_id','=','ordem_de_compra_itens.servico_id');
                $join->on('planejamento_compras.grupo_id','=','ordem_de_compra_itens.grupo_id');
                $join->on('planejamento_compras.subgrupo1_id','=','ordem_de_compra_itens.subgrupo1_id');
                $join->on('planejamento_compras.subgrupo2_id','=','ordem_de_compra_itens.subgrupo2_id');
                $join->on('planejamento_compras.subgrupo3_id','=','ordem_de_compra_itens.subgrupo3_id');
            })->pluck('planejamento_compras.id','planejamento_compras.id')->toArray();
        if(count($planejamento_compras_zerar)){
            PlanejamentoCompra::whereIn('id', $planejamento_compras_zerar)->update(['quantidade_compra'=>0]);
        }

        #limpa sessão
        $request->session()->put('ordemCompra', null);
        $request->session()->forget('ordemCompra');


        Flash::success('Ordem de compra '.$ordemDeCompra->id.' Fechada!');
        return redirect('/ordens-de-compra');
    }

    public function alteraItem($id,Request $request){
        $rules = OrdemDeCompraItem::$rules;
        if(isset($rules[$request->coluna])){
            $this->validate($request,['conteudo'=>$rules[$request->coluna] ]);
        }
        $ordemDeCompraItem = OrdemDeCompraItem::find($id);
        if(!$ordemDeCompraItem){
            return response('Item não encontrado',404)->json(['message'=>'Item não encontrado']);
        }
        $salvo = $ordemDeCompraItem->update([
            $request->coluna => $request->conteudo
        ]);
        return response()->json(['success'=>$salvo]);
    }

    public function uploadAnexos($id, Request $request){
        $ordemDeCompraItem = OrdemDeCompraItem::find($id);
        if(!$ordemDeCompraItem){
            return response('Item não encontrado',404)->json(['message'=>'Item não encontrado']);
        }
        $salvos = 0;
        if(!$request->anexos) {
            return response()->json(['success'=>false, 'error'=>'Nenhum arquivo foi enviado']);
        }

        foreach ($request->anexos as $anexo){
            $arquivo = CodeRepository::saveFile($anexo, 'oc_anexos');
                
            $ordemDeCompraItemAnexo = OrdemDeCompraItemAnexo::create([
                'ordem_de_compra_item_id' => $ordemDeCompraItem->id,
                'arquivo' =>  $arquivo
            ]);
            if($ordemDeCompraItemAnexo){
                $salvos++;
                $ordemDeCompraItem->updated_at = new \DateTime();;
                $ordemDeCompraItem->update();
            }
        }

        $anexos = [];
        if($ordemDeCompraItem->anexos()->count()){
            foreach ($ordemDeCompraItem->anexos as $anexo){
                $anexos[] = [
                    'arquivo' => Storage::url($anexo->arquivo),
                    'arquivo_nome' => substr($anexo->arquivo, strrpos($anexo->arquivo,'/')+1),
                    'id'=> $anexo->id
                ];
            }
        }
        return response()->json(['success'=>($salvos?1:0), 'message'=>'Foram enviados '.$salvos.' arquivos', 'anexos'=>$anexos]);
    }

    public function removerAnexo($id){
        $remover = OrdemDeCompraItemAnexo::find($id);
        if(!$remover){
            return response()->json(['success'=>false, 'error'=>'Nenhum arquivo foi encontrado']);
        }
        if($remover->delete()){
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false, 'error'=>'Erro ao remover']);
    }

    public function indicarContrato(Request $request)
    {
        $insumo = Insumo::where('codigo', $request->codigo_insumo)->first();

        $contrato_insumo = [];

        return response()->json(['contrato_insumo' => $contrato_insumo]);
    }

    public function removerContrato(Request $request)
    {
        $ordem_de_compra = OrdemDeCompraItem::find($request->item);
        $ordem_de_compra->sugestao_contrato_id = null;
        $ordem_de_compra->update();

        return response()->json(['sucesso' => true]);
    }

    public function dashboard(){
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

        $status = OrdemDeCompra::select([
            DB::raw('(
                SELECT `status` FROM `ordem_de_compras` OC1
                JOIN (
                    SELECT
                    z.id,
                    IF(z.igual , 0 , IF(z.maior , 1 , - 1)) AS STATUS
                        FROM
                        (
                            SELECT
                            OC2.id,
                            IF(qtd_total = qtd_itens , 1 , 0) AS igual ,
                                IF(qtd_itens > qtd_total , 1 , 0) AS maior
                                    FROM
                                    (
                                        SELECT
                                        OC3.id,
                                        (
                                            SELECT
                                            SUM(orcamentos.qtd_total) AS total
                                            FROM
                                            ordem_de_compra_itens
                                            INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                            AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                            AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                            AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                            AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                            AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                            AND orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                            AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                            WHERE
                                            orcamentos.orcamento_tipo_id = 1
                                            AND orcamentos.ativo = 1
                                            AND ordem_de_compra_itens.deleted_at IS NULL
                                            AND ordem_de_compra_itens.ordem_de_compra_id = OC3.`id`
                                        ) AS qtd_total ,
                                        (
                                            SELECT
                                            SUM(ordem_de_compra_itens.qtd) AS qtd
                                            FROM
                                            ordem_de_compra_itens
                                            INNER JOIN orcamentos ON orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                            AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                            AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                            AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                            AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                            AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                            AND orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                            AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                            WHERE orcamentos.orcamento_tipo_id = 1
                                            AND ordem_de_compra_itens.deleted_at IS NULL
                                            AND orcamentos.ativo = 1
                                            AND ordem_de_compra_itens.ordem_de_compra_id = OC3.`id`
                                        ) AS qtd_itens
                                        FROM ordem_de_compras OC3
                                    ) AS x
                                    JOIN ordem_de_compras OC2 ON OC2.id = x.id
                                ) AS z
                            ) AS y ON y.id = OC1.id

                            WHERE OC1.id = `ordem_de_compras`.id
                            LIMIT 1
                        ) as status')
                    ])
                    ->get();

        $abaixo_orcamento = 0;
        $dentro_orcamento = 0;
        $acima_orcamento = 0;

        if(count($status)){
            foreach ($status as $item){
                if($item->status == -1){
                    $abaixo_orcamento += 1;
                }

                if($item->status == 0){
                    $dentro_orcamento += 1;
                }

                if($item->status == 1){
                    $acima_orcamento += 1;
                }
            }
        }

        return view('ordem_de_compras.dashboard',compact('reprovados', 'aprovados', 'emaprovacao', 'abaixo_orcamento', 'dentro_orcamento', 'acima_orcamento'));
    }

    public function reabrirOrdemDeCompra($id)
    {
        $ordem_de_compra = OrdemDeCompra::find($id);
        $ordem_de_compra->oc_status_id = 1;
        $ordem_de_compra->aprovado = null;
        $ordem_de_compra->save();

        return redirect('/ordens-de-compra/carrinho?id='.$id);
    }

    public function alterarQuantidade($id, Request $request)
    {
        $ordem_de_compra_item = OrdemDeCompraItem::find($id);
        $ordem_de_compra_item->valor_total = $ordem_de_compra_item->getOriginal('valor_unitario') * money_to_float($request->qtd);
        $ordem_de_compra_item->qtd = $request->qtd;
        $ordem_de_compra_item->aprovado = null;
        $ordem_de_compra_item->save();

        return response()->json(['success'=>true]);
    }

    public function alteraValorUnitario($id, Request $request)
    {
        $ordem_de_compra_item = OrdemDeCompraItem::find($id);
        $ordem_de_compra_item->valor_unitario = $request->valor;
        $ordem_de_compra_item->valor_total = $ordem_de_compra_item->getOriginal('qtd') * money_to_float($request->valor);
        $ordem_de_compra_item->save();

        return response()->json(['success'=>true]);
    }

    public function removerItem($id)
    {
        $ordem_de_compra_item = OrdemDeCompraItem::find($id);
        $ordem_de_compra_item->delete();

        return response()->json(['success'=>true]);
    }

    public function detalhesServicos($obra_id, $servico_id)
    {
        $servico = Servico::find($servico_id);

        if (empty($servico)) {
            Flash::error('Serviço não encontrado');

            return back();
        }

        $ordemDeCompraItens = OrdemDeCompraItem::join('ordem_de_compras', 'ordem_de_compras.id', '=', 'ordem_de_compra_itens.ordem_de_compra_id')
            ->where('ordem_de_compra_itens.servico_id', $servico_id)
            ->whereIn('oc_status_id',[2,3,5]);

        $orcamentoInicial = $totalAGastar = $realizado = 0;

        $itens = collect([]);

        if($ordemDeCompraItens){
            $orcamentoInicial = OrdemDeCompraItem::join('orcamentos', function ($join) use ($servico_id, $obra_id) {
                    $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                    $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                    $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                    $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                    $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                    $join->on('orcamentos.servico_id','=', DB::raw($servico_id));
                    $join->on('orcamentos.obra_id','=', DB::raw($obra_id));
                    $join->on('orcamentos.ativo','=', DB::raw('1'));
                })
                ->sum('orcamentos.preco_total');

            $totalAGastar = $ordemDeCompraItens->sum('valor_total');

            $realizado = OrdemDeCompraItem::join('ordem_de_compras','ordem_de_compras.id','=','ordem_de_compra_itens.ordem_de_compra_id')
                ->where('ordem_de_compras.obra_id',$obra_id)
                ->whereIn('oc_status_id',[2,3,5])
                ->whereIn('ordem_de_compra_itens.insumo_id',$ordemDeCompraItens->pluck('insumo_id','insumo_id')->toArray())
                ->sum('ordem_de_compra_itens.valor_total');

            $saldo = $orcamentoInicial - $realizado;

            $itens = OrdemDeCompraItem::select([
                    'ordem_de_compra_itens.*',
                    DB::raw('0 as qtd_realizada'),
                    DB::raw('0 as valor_realizado'),
                    'orcamentos.qtd_total as qtd_inicial',
                    'orcamentos.preco_total as preco_inicial',
                    DB::raw("(SELECT SUM( qtd )
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as qtd"),
                    DB::raw("(SELECT SUM( valor_total )
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as valor_total"),
                    DB::raw("(SELECT GROUP_CONCAT(ordem_de_compras.id SEPARATOR ',')
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as ordem_de_compras_ids"),
                    DB::raw("(SELECT GROUP_CONCAT(OCI2.justificativa SEPARATOR ',')
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as justificativas"),
                    DB::raw("(SELECT GROUP_CONCAT(OCI2.obs SEPARATOR ',')
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as obs"),
                    DB::raw("(SELECT GROUP_CONCAT(ordem_de_compra_item_anexos.arquivo SEPARATOR ',')
                    FROM ordem_de_compra_itens OCI2
                    JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                    JOIN ordem_de_compra_item_anexos ON ordem_de_compra_item_anexos.ordem_de_compra_item_id = OCI2.id
                    WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND (
                        ordem_de_compras.oc_status_id = 2
                        OR ordem_de_compras.oc_status_id = 3
                        OR ordem_de_compras.oc_status_id = 5
                    )
                    AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                    AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                    AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                    AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                    AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                    AND OCI2.servico_id = ".$servico_id."
                    AND OCI2.obra_id = ".$obra_id."
                    AND OCI2.deleted_at IS NULL
                ) as anexos"),
                    DB::raw("(SELECT GROUP_CONCAT(OCI2.sugestao_contrato_id SEPARATOR ',')
                        FROM ordem_de_compra_itens OCI2
                        JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                        WHERE OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                        AND (
                            ordem_de_compras.oc_status_id = 2
                            OR ordem_de_compras.oc_status_id = 3
                            OR ordem_de_compras.oc_status_id = 5
                        )
                        AND OCI2.insumo_id = ordem_de_compra_itens.insumo_id
                        AND OCI2.grupo_id = ordem_de_compra_itens.grupo_id
                        AND OCI2.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                        AND OCI2.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                        AND OCI2.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                        AND OCI2.servico_id = ".$servico_id."
                        AND OCI2.obra_id = ".$obra_id."
                        AND OCI2.deleted_at IS NULL
                    ) as contratos")
                ])
                ->join('orcamentos', function ($join) use ($servico_id, $obra_id){
                    $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                    $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                    $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                    $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                    $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                    $join->on('orcamentos.servico_id','=', DB::raw($servico_id));
                    $join->on('orcamentos.obra_id','=', DB::raw($obra_id));
                    $join->on('orcamentos.ativo','=', DB::raw('1'));
                })
                ->with('insumo','unidade','anexos');
        }

        $itens = $itens->groupBy('orcamentos.insumo_id')->paginate(10);

        return view('ordem_de_compras.detalhes_servicos', compact(
                'ordemDeCompra',
                'orcamentoInicial',
                'realizado',
                'totalAGastar',
                'saldo',
                'itens',
                'servico'
            )
        );
    }

    public function insumosAprovados(InsumosAprovadosDataTable $insumosAprovadosDataTable){
        # Traz apenas os que existem OCs aprovadas
        $insumosAprovados =
            OrdemDeCompraItem::join('ordem_de_compras','ordem_de_compras.id','ordem_de_compra_itens.ordem_de_compra_id')
            ->where('ordem_de_compras.aprovado','1')
            ->whereNotExists(function ($query){
                $query->select(DB::raw('1'))
                    ->from('oc_item_qc_item')
                    ->join('qc_itens','qc_itens.id','oc_item_qc_item.qc_item_id')
                    ->join('quadro_de_concorrencias','quadro_de_concorrencias.id','qc_itens.quadro_de_concorrencia_id')
                    ->where('ordem_de_compra_item_id',DB::raw('ordem_de_compra_itens.id') )
                    ->where('quadro_de_concorrencias.qc_status_id','!=','6');
            });

        $cidades = Cidade::whereIn('id', $insumosAprovados->groupBy('obras.cidade_id')
            ->join('obras','obras.id','ordem_de_compra_itens.obra_id')
            ->pluck('obras.cidade_id', 'obras.cidade_id')
            ->toArray())->pluck('nome','id')->toArray();

        $obras = Obra::whereIn('id', $insumosAprovados->groupBy('ordem_de_compra_itens.obra_id')
            ->pluck('ordem_de_compra_itens.obra_id', 'ordem_de_compra_itens.obra_id')
            ->toArray())->pluck('nome','id')->toArray();

        $OCs = OrdemDeCompra::whereIn('id',$insumosAprovados->groupBy('ordem_de_compra_itens.ordem_de_compra_id')
            ->pluck('ordem_de_compra_itens.ordem_de_compra_id', 'ordem_de_compra_itens.ordem_de_compra_id')
            ->toArray())->pluck('id','id')->toArray();

        $insumoGrupos = InsumoGrupo::whereIn('id',$insumosAprovados
            ->join('insumos', 'insumos.id','ordem_de_compra_itens.insumo_id')
            ->groupBy('insumo_grupo_id')
            ->pluck('insumo_grupo_id', 'insumo_grupo_id')
            ->toArray()
        )
        ->pluck('nome','id')
        ->toArray();

        $insumos = Insumo::whereIn('id',$insumosAprovados
            ->groupBy('ordem_de_compra_itens.insumo_id')
            ->pluck('ordem_de_compra_itens.insumo_id', 'ordem_de_compra_itens.insumo_id')
            ->toArray()
        )
        ->pluck('nome','id')
        ->toArray();

        $farol = [
            'vermelho'=>'Vermelho',
            'amarelo'=>'Amarelo',
            'verde'=>'Verde',
        ];
        return $insumosAprovadosDataTable->render('ordem_de_compras.insumos-aprovados',
            compact('obras','OCs','insumoGrupos','insumos','cidades','farol'));
    }

    /**
     * Tela de inserção de insumos no orçamento.
     * @param Obra $obra_id
     * @return Render View
     */
    public function insumosOrcamento($obra_id)
    {
        $grupos = Grupo::whereNull('grupo_id')
            ->select([
                'id',
                DB::raw("CONCAT(codigo, ' ', nome) as nome")
            ])
            ->pluck('nome','id')
            ->toArray();

        return view('ordem_de_compras.insumos_orcamento', compact('obra_id', 'grupos'));
    }

    /**
     * Método de inserir insumo no orçamento.
     * @param Request $request
     * @return redirect
     */
    public function incluirInsumosOrcamento(Request $request)
    {
        $insumo = Insumo::find($request->insumo_id);
        $servico = Servico::find($request->servico_id);

        $orcamento = new Orcamento([
            'obra_id' => $request->obra_id,
            'codigo_insumo' => $servico->codigo . '.' . $insumo->codigo,
            'insumo_id' => $request->insumo_id,
            'servico_id' => $request->servico_id,
            'grupo_id' => $request->grupo_id,
            'unidade_sigla' => $insumo->unidade_sigla,
            'preco_unitario' => 0,
            'qtd_total' => $request->qtd_total,
            'orcamento_tipo_id' => 1,
            'subgrupo1_id' => $request->subgrupo1_id,
            'subgrupo2_id' => $request->subgrupo2_id,
            'subgrupo3_id' => $request->subgrupo3_id,
            'user_id' => Auth::id(),
            'descricao' => $insumo->nome
        ]);

        $orcamento->save();

        return redirect('/compras/insumos/orcamento/'.$request->obra_id)->with(['salvo' => true]);
    }

    /**
     * Método para cadastrar novo grupo.
     * @param Request $request
     * @return true
     */
    public function cadastrarGrupo(Request $request)
    {
        $salvo = false;
        $grupo = [];
        if($request->codigo_grupo && $request->nome_grupo) {
            if ($request->subgrupo_de_nome == 'servico_id') {
                $grupo_com_cod = Grupo::find($request->subgrupo_de);
                $grupo = new Servico([
                    'codigo' => $grupo_com_cod->codigo . '.' . $request->codigo_grupo,
                    'nome' => $request->nome_grupo,
                    'grupo_id' => $request->subgrupo_de ? $request->subgrupo_de : null
                ]);
                $salvo = $grupo->save();
            } else {
                $grupo = new Grupo([
                    'codigo' => $request->codigo_grupo ? $request->codigo_grupo : null,
                    'nome' => $request->nome_grupo ? $request->nome_grupo : null,
                    'grupo_id' => $request->subgrupo_de ? $request->subgrupo_de : null
                ]);
                $salvo = $grupo->save();
            }
        }

        return response()->json(['salvo' => $salvo, 'grupo' => $grupo]);
    }

    public function totalParcial(Request $request, Obra $obra)
    {
        //Testa se tem ordem de compra aberta pro user
        $ordem = null;
        if(\Session::get('ordemCompra')){
            $ordem = OrdemDeCompra::where('id', \Session::get('ordemCompra'))
                ->where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request->obra_id)->first();
        }else {
            $ordem = OrdemDeCompra::where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request->obra_id)->first();
        }

        if(!$ordem){
            $ordem = new OrdemDeCompra();
            $ordem->oc_status_id = 1;
            $ordem->obra_id = $request->obra_id;
            $ordem->user_id = Auth::user()->id;
            $ordem->save();
            OrdemDeCompraStatusLog::create([
                'oc_status_id'=>1,
                'ordem_de_compra_id'=>$ordem->id,
                'user_id'=>Auth::id()
            ]);

            # Colocando na sessão
            $request->session()->put('ordemCompra', $ordem->id);
        }

        // Encontra o orçamento ativo
        $orcamento_ativo = Orcamento::where('insumo_id',$request->id)
            ->where('obra_id',$obra->id)
            ->where('grupo_id',$request->grupo_id)
            ->where('subgrupo1_id',$request->subgrupo1_id)
            ->where('subgrupo2_id',$request->subgrupo2_id)
            ->where('subgrupo3_id',$request->subgrupo3_id)
            ->where('servico_id',$request->servico_id)
            ->where('ativo',1)
            ->first();

        $ordem_item = OrdemDeCompraItem::where('ordem_de_compra_id', $ordem->id)
            ->where('obra_id', $obra->id)
            ->where('codigo_insumo', $orcamento_ativo->codigo_insumo)
            ->where('grupo_id', $orcamento_ativo->grupo_id)
            ->where('subgrupo1_id', $orcamento_ativo->subgrupo1_id)
            ->where('subgrupo2_id', $orcamento_ativo->subgrupo2_id)
            ->where('subgrupo3_id', $orcamento_ativo->subgrupo3_id)
            ->where('servico_id', $orcamento_ativo->servico_id)
            ->where('insumo_id', $orcamento_ativo->insumo_id)
            ->where('unidade_sigla', $orcamento_ativo->unidade_sigla)
            ->first();

        //        dd($ordem_item);

        if($ordem_item->total == 1){
            $ordem_item->total = 0;
        }else{
            $ordem_item->total = 1;
        }
        $ordem_item->save();

        return response()->json(200);
    }

    public function comprarTudo(Request $request, Obra $obra)
    {
        $insumo_collection =  new Collection($request->all());
        self::comprarTudoItem($insumo_collection, $obra->id);

        return response()->json(200);
    }

    public function comprarTudoDeTudo(Request $request)
    {
        $query = $request->session()->get('query['.$request->random.']');
        $bindings = $request->session()->get('bindings['.$request->random.']');

        $insumos = DB::select($query,
            $bindings);

        foreach ($insumos as $insumo){
            if(money_to_float($insumo->saldo) > 0) {
                $insumo_collection = new Collection($insumo);
                self::comprarTudoItem($insumo_collection, $insumo_collection['obra_id']);
            }
        }

        return response()->json(200);
    }

    public function comprarTudoItem($request, $obra_id)
    {
        //Testa se tem ordem de compra aberta pro user
        $ordem = null;
        if(\Session::get('ordemCompra')){
            $ordem = OrdemDeCompra::where('id', \Session::get('ordemCompra'))
                ->where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request['obra_id'])->first();
        }else {
            $ordem = OrdemDeCompra::where('oc_status_id', 1)
                ->where('user_id', Auth::user()->id)
                ->where('obra_id', $request['obra_id'])->first();
        }

        if(!$ordem){
            $ordem = new OrdemDeCompra();
            $ordem->oc_status_id = 1;
            $ordem->obra_id = $request['obra_id'];
            $ordem->user_id = Auth::user()->id;
            $ordem->save();
            OrdemDeCompraStatusLog::create([
                'oc_status_id'=>1,
                'ordem_de_compra_id'=>$ordem->id,
                'user_id'=>Auth::id()
            ]);

            # Colocando na sessão
            \Session::put('ordemCompra', $ordem->id);
        }

        // Encontra o orçamento ativo para validar preço
        $orcamento_ativo = Orcamento::where('insumo_id',$request['id'])
            ->where('obra_id',$obra_id)
            ->where('grupo_id',$request['grupo_id'])
            ->where('subgrupo1_id',$request['subgrupo1_id'])
            ->where('subgrupo2_id',$request['subgrupo2_id'])
            ->where('subgrupo3_id',$request['subgrupo3_id'])
            ->where('servico_id',$request['servico_id'])
            ->where('ativo',1)
            ->first();


        $ordem_item = OrdemDeCompraItem::firstOrNew([
            'ordem_de_compra_id' => $ordem->id,
            'obra_id' => $obra_id,
            'codigo_insumo' => $orcamento_ativo->codigo_insumo,
            'grupo_id' => $orcamento_ativo->grupo_id,
            'subgrupo1_id' => $orcamento_ativo->subgrupo1_id,
            'subgrupo2_id' => $orcamento_ativo->subgrupo2_id,
            'subgrupo3_id' => $orcamento_ativo->subgrupo3_id,
            'servico_id' => $orcamento_ativo->servico_id,
            'insumo_id' => $orcamento_ativo->insumo_id,
            'unidade_sigla' => $orcamento_ativo->unidade_sigla,
        ]);


        $ordem_item->user_id = Auth::user()->id;
        $ordem_item->qtd = $request['qtd_total'];
        $ordem_item->valor_unitario = $orcamento_ativo->preco_unitario;
        $ordem_item->valor_total = $orcamento_ativo->getOriginal('preco_unitario') * money_to_float($ordem_item->qtd);
        $ordem_item->save();
    }

    public function getGrupos($id){
        $grupo = Grupo::select([
                'id',
                DB::raw("CONCAT(codigo, ' ', nome) as nome")
            ])
            ->where('grupo_id', $id)
            ->pluck('nome','id')->toArray();
        return $grupo;
    }
    public function getServicos($id){
        $servico = Servico::select([
                'id',
                DB::raw("CONCAT(codigo, ' ', nome) as nome")
            ])
            ->where('grupo_id', $id)
            ->pluck('nome', 'id')->toArray();
        return $servico;
    }

    /**
     * Tela para realizar a troca de insumos
     *
     * @return Response
     */
    public function trocar(
        Request $request,
        OrcamentoRepository $orcamentoRepository,
        $orcamentoId
    ) {
        $orcamento = $orcamentoRepository->findWithoutFail($orcamentoId);

        if (empty($orcamento)) {
            Flash::error(
                'Orcamento selecionado não encontrado'
            );

            return back()->withInput();
        }

        return view('ordem_de_compras.trocar', compact('orcamento'));
    }

    /**
     * Tela para realizar a troca de insumos
     *
     * @return Response
     */
   public function trocarSave(
        Request $request,
        OrcamentoRepository $orcamentoRepository,
        InsumoRepository $insumoRepository,
        $orcamentoId)
   {
        $orcamento = $orcamentoRepository->findWithoutFail($orcamentoId);

        if (empty($orcamento)) {
            Flash::error(
                'Orcamento selecionado não encontrado'
            );

            return back()->withInput();
        }

        DB::beginTransaction();

        try {
            $orcamento->update(['trocado' => 1]);

            collect($request->data)
                ->map(function($data) use ($insumoRepository) {
                    $data['insumo'] = $insumoRepository->find($data['insumo_id']);

                    return (object) $data;
                })
                ->each(function($data) use ($orcamento) {
                    $troca                          = $orcamento->replicate();
                    $troca->insumo_id               = $data->insumo->id;
                    $troca->qtd_total               = $data->qtd_total;
                    $troca->descricao               = $data->insumo->nome;
                    $troca->unidade_sigla           = $data->insumo->unidade_sigla;
                    $troca->orcamento_que_substitui = $orcamento->id;
                    $troca->save();
                });
        } catch (Exception $e) {
            DB::rollback();
            Flash::error('Ocorreu um problema! Não foi possível salvar os dados.');

            logger()->error((string) $e);

            return back();
        }

        DB::commit();

        Flash::success('Troca realizada com sucesso');

        return redirect($request->back ?: url()->previous());
   }

   public function buscaPlanejamentos(Request $request)
   {
       return Planejamento::select([
           'id',
           'tarefa'
       ])
           ->where('tarefa','like', '%'.$request->q.'%')->paginate();
   }

   public function buscaInsumoGrupos(Request $request)
   {
       return InsumoGrupo::select([
           'id',
           'nome'
       ])
           ->where('nome','like', '%'.$request->q.'%')->paginate();
   }

}

