<?php

namespace App\Http\Controllers;

use App\DataTables\OrdemDeCompraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateOrdemDeCompraRequest;
use App\Http\Requests\UpdateOrdemDeCompraRequest;
use App\Models\Insumo;
use App\Models\Grupo;
use App\Models\InsumoGrupo;
use App\Models\Lembrete;
use App\Models\OrdemDeCompraItemAnexo;
use App\Models\Planejamento;
use App\Models\PlanejamentoCompra;
use App\Models\WorkflowReprovacaoMotivo;
use App\Repositories\CodeRepository;
use function foo\func;
use Illuminate\Pagination\Paginator;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\OrdemDeCompraItem;

use App\Models\OrdemDeCompra;

use App\Repositories\OrdemDeCompraRepository;
use App\Repositories\WorkflowAprovacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

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

        return redirect(route('ordemDeCompras.index'));
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

            return redirect(route('ordemDeCompras.index'));
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

            return redirect(route('ordemDeCompras.index'));
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

            return redirect(route('ordemDeCompras.index'));
        }

        $ordemDeCompra = $this->ordemDeCompraRepository->update($request->all(), $id);

        Flash::success('Ordem De Compra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('ordemDeCompras.index'));
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

            return redirect(route('ordemDeCompras.index'));
        }

        $this->ordemDeCompraRepository->delete($id);

        Flash::success('Ordem De Compra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('ordemDeCompras.index'));
    }

    public function compras()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('ordem_de_compras.compras', compact('obras'));
    }

    public function detalhe($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return back();
        }

        $orcamentoInicial = $totalAGastar = $realizado = 0;


        $itens = collect([]);

        $aprovavelTudo = WorkflowAprovacaoRepository::verificaAprovaGrupo('OrdemDeCompraItem', $ordemDeCompra->itens()->pluck('id','id')->toArray(), Auth::user() );

        if($ordemDeCompra->itens){
            $orcamentoInicial = Orcamento::where('orcamento_tipo_id',1)
                ->whereIn('insumo_id', $ordemDeCompra->itens()->pluck('insumo_id','insumo_id')->toArray())
                ->where('ativo','1')
                ->where('obra_id',$ordemDeCompra->obra_id)
                ->sum('preco_total');

            $totalAGastar = $ordemDeCompra->itens()->sum('valor_total');

            $realizado = OrdemDeCompraItem::join('ordem_de_compras','ordem_de_compras.id','=','ordem_de_compra_itens.ordem_de_compra_id')
                ->where('ordem_de_compras.obra_id',$ordemDeCompra->obra_id)
                ->whereIn('oc_status_id',[2,3,5])
                ->sum('valor_total');

            $saldo = $orcamentoInicial - $totalAGastar - $realizado;

            $itens = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
                ->select([
                    'ordem_de_compra_itens.*',
                    DB::raw("(SELECT SUM( qtd ) 
                                FROM ordem_de_compra_itens OCI2
                                JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                                WHERE ordem_de_compras.id = ordem_de_compra_itens.ordem_de_compra_id
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
                             ) as qtd_realizada"),
                    DB::raw("(SELECT SUM( valor_total ) 
                                FROM ordem_de_compra_itens OCI2
                                JOIN ordem_de_compras ON ordem_de_compras.id = OCI2.ordem_de_compra_id
                                WHERE ordem_de_compras.id = ordem_de_compra_itens.ordem_de_compra_id
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
                             ) as valor_realizado"),
                    'orcamentos.qtd_total as qtd_inicial',
                    'orcamentos.preco_total as preco_inicial',
                ])
                ->join('orcamentos', function ($join){
                    $join->on('orcamentos.insumo_id','=', 'ordem_de_compra_itens.insumo_id');
                    $join->on('orcamentos.grupo_id','=', 'ordem_de_compra_itens.grupo_id');
                    $join->on('orcamentos.subgrupo1_id','=', 'ordem_de_compra_itens.subgrupo1_id');
                    $join->on('orcamentos.subgrupo2_id','=', 'ordem_de_compra_itens.subgrupo2_id');
                    $join->on('orcamentos.subgrupo3_id','=', 'ordem_de_compra_itens.subgrupo3_id');
                    $join->on('orcamentos.servico_id','=', 'ordem_de_compra_itens.servico_id');
                    $join->on('orcamentos.ativo','=', DB::raw('1'));
                })
                ->with('insumo','unidade','anexos')
                ->paginate(2);
        }


        $motivos_reprovacao = WorkflowReprovacaoMotivo::pluck('nome','id')->toArray();

        return view('ordem_de_compras.detalhe', compact(
                'ordemDeCompra',
                'orcamentoInicial',
                'realizado',
                'totalAGastar',
                'saldo',
                'itens',
                'motivos_reprovacao',
                'aprovavelTudo'
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
    public function insumos(Planejamento $planejamento, InsumoGrupo $insumoGrupo){
        return view('ordem_de_compras.insumos', compact('planejamento', 'insumoGrupo'));
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
    public function insumosJson(Request $request, Planejamento $planejamento){
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
                AND planejamento_compras.planejamento_id ='.$planejamento->id.') as adicionado')
            ])->where('deleted_at','=', null);

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
    public function insumosAdd(Request $request, Planejamento $planejamento)
    {
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
            $planejamento_compras->save();
            Flash::success('Insumo adicionado com sucesso');
            return response()->json('{response: "sucesso"}');
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
    public function obrasInsumos(Request $request, Planejamento $planejamento, InsumoGrupo $insumoGrupo)
    {
        return view('ordem_de_compras.obras_insumos', compact('planejamento', 'insumoGrupo'));
    }

    /**
     * Método que retorna a lista de filtros aplicaveis a obras insumos.
     *
     *
     * @return Json
     */
    public function obrasInsumosFilters(){
        $filters = OrdemDeCompra::$filters_obras_insumos;
        return response()->json($filters);
    }

    /**
     * Método que retorna a lista de insumos de uma tarefa como json.
     *
     * @param  Request $request ->planejamento_id
     *
     * @filters obrasInsumosFilters()
     *
     * @return Json
     */
    public function obrasInsumosJson(Request $request, Planejamento $planejamento, InsumoGrupo $insumoGrupo)
    {
        //Query para utilização dos filtros
        $insumo_query = Insumo::query();

        //Query pra trazer
        $insumos = $insumo_query->join('planejamento_compras', function ($join) use ($planejamento){
            $join->on('insumos.id', 'planejamento_compras.insumo_id')
                ->where('planejamento_compras.planejamento_id','=', $planejamento->id);
        })->join('orcamentos', 'orcamentos.insumo_id', 'insumos.id')
            ->select(
                [
                    'insumos.id',
                    'insumos.nome',
                    'insumos.unidade_sigla',
                    'insumos.codigo',
                    'orcamentos.grupo_id',
                    'orcamentos.subgrupo1_id',
                    'orcamentos.subgrupo2_id',
                    'orcamentos.subgrupo3_id',
                    'orcamentos.servico_id',
                    'orcamentos.qtd_total',
                    'orcamentos.preco_total',
                    'orcamentos.preco_unitario',
                    'planejamento_compras.quantidade_compra',
                    'planejamento_compras.id as planejamento_compra_id',
                    DB::raw('(SELECT count(id) FROM planejamento_compras 
                    WHERE planejamento_compras.insumo_id = insumos.id 
                    AND planejamento_compras.planejamento_id ='.$planejamento->id.' AND  planejamento_compras.insumo_pai IS NOT NULL) as filho'),
                    DB::raw('(SELECT count(id) FROM planejamento_compras 
                    WHERE planejamento_compras.planejamento_id ='.$planejamento->id.' AND  planejamento_compras.insumo_pai = insumos.id) as pai'),
                    DB::raw('(SELECT count(id) FROM ordem_de_compra_itens 
                    WHERE ordem_de_compra_itens.insumo_id = insumos.id 
                    AND ordem_de_compra_itens.obra_id ='.$planejamento->obra_id.') as adicionado')
                ]
            )->where('deleted_at','=', null)
            ->orderBy(DB::raw(' COALESCE (planejamento_compras.id, planejamento_compras.trocado_de), planejamento_compras.trocado_de'));


        //Testa a ordenação
        if(isset($request->orderkey)){
            $insumo_query->orderBy($request->orderkey, $request->order);
        }
        //Aplica filtro do Jhonatan
        $insumos = CodeRepository::filter($insumos, $request->all());

        return response()->json($insumos->paginate(10), 200);
    }

    public function removerInsumoPlanejamento(PlanejamentoCompra $planejamentoCompra)
    {
         PlanejamentoCompra::destroy($planejamentoCompra->id);
         return response()->redirect()->back();
    }


    public function addCarrinho(Request $request, Planejamento $planejamento)
    {
        //Testa se tem ordem de compra aberta pro user
        $ordem = OrdemDeCompra::where('oc_status_id', 1)
            ->where('user_id', Auth::user()->id)
            ->where('obra_id', $planejamento->obra_id)->first();

        if($ordem){
            $ordem_item = new OrdemDeCompraItem();
            $ordem_item->ordem_de_compra_id = $ordem->id;
            $ordem_item->obra_id = $planejamento->obra_id;
            $ordem_item->codigo_insumo = $request->codigo;
            $ordem_item->qtd = $request->quantidade_compra;
            $ordem_item->valor_unitario = $request->preco_unitario;
            $ordem_item->valor_total = $request->preco_total;
            $ordem_item->grupo_id = $request->grupo_id;
            $ordem_item->subgrupo1_id = $request->subgrupo1_id;
            $ordem_item->subgrupo2_id = $request->subgrupo2_id;
            $ordem_item->subgrupo3_id = $request->subgrupo3_id;
            $ordem_item->servico_id = $request->servico_id;
            $ordem_item->insumo_id = $request->id;
            $ordem_item->user_id = Auth::user()->id;
            $ordem_item->unidade_sigla = $request->unidade_sigla;
            $ordem_item->save();
        }else{
            $ordem = new OrdemDeCompra();
            $ordem->oc_status_id = 1;
            $ordem->obra_id = $planejamento->obra_id;
            $ordem->user_id = Auth::user()->id;
            $ordem->save();

            $ordem_item = new OrdemDeCompraItem();
            $ordem_item->ordem_de_compra_id = $ordem->id;
            $ordem_item->obra_id = $planejamento->obra_id;
            $ordem_item->codigo_insumo = $request->codigo;
            $ordem_item->qtd = $request->quantidade_compra;
            $ordem_item->valor_unitario = $request->preco_unitario;
            $ordem_item->valor_total = $request->preco_total;
            $ordem_item->grupo_id = $request->grupo_id;
            $ordem_item->subgrupo1_id = $request->subgrupo1_id;
            $ordem_item->subgrupo2_id = $request->subgrupo2_id;
            $ordem_item->subgrupo3_id = $request->subgrupo3_id;
            $ordem_item->servico_id = $request->servico_id;
            $ordem_item->insumo_id = $request->id;
            $ordem_item->user_id = Auth::user()->id;
            $ordem_item->unidade_sigla = $request->unidade_sigla;
            $ordem_item->save();
        }
    }


    /**
     * Tela que traz as opcoes de troca de insumos.
     *
     * @param  Insumo $insumo
     * @param  Planejamento $planejamento
     * @param  InsumoGrupo $insumoGrupo
     * @return Render View
     */
    public function trocaInsumos(Planejamento $planejamento, InsumoGrupo $insumoGrupo, Insumo $insumo)
    {
        return view('ordem_de_compras.troca_insumos', compact('planejamento', 'insumoGrupo', 'insumo'));
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


    public function trocaInsumoAction(Request $request, Planejamento $planejamento,Insumo $insumo)
    {
        try{
            $planejamento_pai = PlanejamentoCompra::where('insumo_id', $insumo->id)->where('planejamento_id',$planejamento->id)->first();
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
            $planejamento_compras->save();
            Flash::success('Insumo adicionado com sucesso');
            return response()->json('{response: "sucesso"}');
        }catch (\Exception $e){
            Flash::error('Insumo adicionado com'. $e->getMessage());
            return response()->json('{response: "error'.$e->getMessage().'"}');
        }
    }

    public function trocaInsumosJsonFilho(Planejamento $planejamento,Insumo $insumo){
        $insumo_query = Insumo::query();

        $planejamento_pai = PlanejamentoCompra::where('insumo_id',$insumo->id)
            ->where('planejamento_id', $planejamento->id)->first();
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
            ->where('planejamento_compras.insumo_pai',$insumo->id);
//            ->where('planejamento_compras.trocado_de',$insumo->id);
//            ->whereNotNull('planejamento_compras.trocado_de');
        return response()->json($insumos->paginate(10), 200);
    }

    public function trocaInsumosJsonPai(Insumo $insumo){
        $insumo_query = Insumo::query();

        //Query pra trazer
        $insumos = $insumo_query->join('orcamentos','orcamentos.insumo_id','=','insumos.id')
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'orcamentos.grupo_id',
                'orcamentos.servico_id',
                'orcamentos.qtd_total',
                'orcamentos.preco_total'
            ])
            ->where('insumos.id',$insumo->id);

        return response()->json($insumos->paginate(10), 200);
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
            $ordemDeCompra->where('obra_id');
        }
        $ordemDeCompra = $ordemDeCompra->first();

        if (empty($ordemDeCompra)) {
            Flash::error('Não existe OC em aberto.');

            return back();
        }

        $itens = collect([]);

        if($ordemDeCompra->itens){
            $itens = OrdemDeCompraItem::where('ordem_de_compra_id', $ordemDeCompra->id)
                ->with('insumo','unidade','anexos')
                ->paginate(10);
        }

        return view('ordem_de_compras.carrinho', compact(
                'ordemDeCompra',
                'itens'
            )
        );
    }

    public function fechaCarrinho(Request $request){
        $ordemDeCompra = OrdemDeCompra::where('oc_status_id',1)->where('user_id',Auth::id());
        if($request->obra_id){
            $ordemDeCompra->where('obra_id');
        }
        $ordemDeCompra = $ordemDeCompra->first();

        if (empty($ordemDeCompra)) {
            Flash::error('Não existe OC em aberto.');

            return back();
        }
        $ordemDeCompra->oc_status_id = 2; // Fechada
        $ordemDeCompra->save();

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
            $arquivo = $anexo->storeAs(
                'public/oc_anexos', str_replace('.'.$anexo->clientExtension(), '', $anexo->getClientOriginalName()).'_'.rand(100,10000).'.'.$anexo->clientExtension()
            );
            $ordemDeCompraItemAnexo = OrdemDeCompraItemAnexo::create([
                'ordem_de_compra_item_id' => $ordemDeCompraItem->id,
                'arquivo' =>  $arquivo
            ]);
            if($ordemDeCompraItemAnexo){
                $salvos++;
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

    function removerAnexo($id){
        $remover = OrdemDeCompraItemAnexo::find($id);
        if(!$remover){
            return response()->json(['success'=>false, 'error'=>'Nenhum arquivo foi encontrado']);
        }
        if($remover->delete()){
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false, 'error'=>'Erro ao remover']);
    }
}

