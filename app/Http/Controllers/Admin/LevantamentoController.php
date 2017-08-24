<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LevantamentoDataTable;
use App\DataTables\Admin\LevantamentoMascaraInsumoDataTable;
use App\DataTables\Admin\LevantamentoMascaraEstruturaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\LevantamentoRequest;
use App\Http\Requests\Admin\UpdateLevantamentoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\PlanejamentoCompra;
use App\Models\Planilha;
use App\Models\Servico;
use App\Models\TemplatePlanilha;
use App\Repositories\Admin\LevantamentoRepository;
use App\Repositories\Admin\SpreadsheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

class LevantamentoController extends AppBaseController
{
    /** @var  LevantamentoRepository */
    private $levantamentoRepository;

    public function __construct(LevantamentoRepository $levantamentoRepo)
    {
        $this->levantamentoRepository = $levantamentoRepo;
    }

    /**
     * Display a listing of the Levantamento.
     *
     * @param LevantamentoDataTable $levantamentoDataTable
     * @return Response
     */
    public function index(Request $request, LevantamentoDataTable $levantamentoDataTable)
    {
        $id = null;
        if($request->id){
            $id = $request->id;
        }
        return $levantamentoDataTable->porObra($id)->render('admin.levantamentos.index');
    }
	
	/**
     * Display a listing of the Mascara.
     *
     * @param LevantamentoMascaraDataTable $LevantamentoMascaraDataTable
     * @return Response
     */
	public function mascaraInsumo(Request $request, LevantamentoMascaraInsumoDataTable $levantamentoMascaraInsumoDataTable)
    {
        $id = null;
        if($request->id){
            $id = $request->id;
        }
		
        return $levantamentoMascaraInsumoDataTable->render('admin.levantamentos.mascara_insumo');
    }
	
	/**
     * Display a listing of the Mascara.
     *
     * @param LevantamentoMascaraDataTable $LevantamentoMascaraDataTable
     * @return Response
     */
	public function mascaraEstrutura(Request $request, LevantamentoMascaraEstruturaDataTable $levantamentoMascaraEstruturaDataTable)
    {
        $id = null;
        if($request->id){
            $id = $request->id;
        }
		
        return $levantamentoMascaraEstruturaDataTable->render('admin.levantamentos.mascara_estrutura');
    }

    /**
     * Show the form for creating a new Levantamento.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('admin.levantamentos.create', compact('obras'));
    }

    /**
     * Store a newly created Planejamento in storage.
     *
     * @param CreatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePlanejamentoRequest $request)
    {
        $input = $request->all();

        $levantamento = $this->levantamentoRepository->create($input);

        Flash::success('Levantamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.levantamentos.index'));
    }

    /**
     * Display the specified Levantamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $levantamento = $this->levantamentoRepository->findWithoutFail($id);        

        if (empty($levantamento)) {
            Flash::error('Levantamento '.trans('common.not-found'));

            return redirect(route('admin.levantamentos.index'));
        }

        return view('admin.levantamentos.show', compact('levantamento'));
    }

    /**
     * Show the form for editing the specified Levantamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $obras = Obra::pluck('nome','id')->toArray();
        $levantamento = $this->levantamentoRepository->findWithoutFail($id);

        if (empty($levantamento)) {
            Flash::error('Levantamento '.trans('common.not-found'));

            return redirect(route('admin.levantamentos.index'));
        }


        return view('admin.levantamentos.edit', compact('levantamento','obras'));
    }

    /**
     * Update the specified Levantamento in storage.
     *
     * @param  int              $id
     * @param UpdatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLevantamentoRequest $request)
    {
        $levantamento = $this->levantamentoRepository->findWithoutFail($id);

        if (empty($levantamento)) {
            Flash::error('Levantamento '.trans('common.not-found'));

            return redirect(route('admin.levantamentos.index'));
        }
        $input = $request->all();

        $levantamento = $this->levantamentoRepository->update($input, $id);

        Flash::success('Levantamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.levantamentos.index'));
    }

    /**
     * Remove the specified Levantamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $levantamento = $this->levantamentoRepository->findWithoutFail($id);

        if (empty($levantamento)) {
            Flash::error('Levantamento '.trans('common.not-found'));

            return redirect(route('admin.levantamentos.index'));
        }

        $this->levantamentoRepository->delete($id);

        Flash::success('Levantamento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.levantamentos.index'));
    }


    ################################ IMPORTAÇÃO ###################################

    /**
     * $obras = Buscando chave e valor para fazer o combobox da view
     * $orcamento_tipos = Buscando chave e valor para fazer o combobox da view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexImport(Request $request){
        
		$id = null;
        if($request->id){
            $id = $request->id;
        }

        $obras = Obra::pluck('nome','id')->toArray();
        $templates = TemplatePlanilha::where('modulo', 'Levantamentos')->pluck('nome','id')->toArray();
        return view('admin.levantamentos.indexImport', compact('obras','templates','id'));
    }

    /**
     * $request = Recebendo campos na view
     * $file = Pegando campos request exceto os que está dentro da exceção
     * $input = Pegando campos request exceto os campos que está dentro da exceção
     * $input['user_id'] = pegando usuário logado
     * $parametros = pegando $input e tranformando em json
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function import(Request $request)
    {
        $tipo = 'levantamento';
        $file = $request->except('obra_id','template_id');
        $input = $request->except('_token','file');
        $template = $request->template_id;
        $input['user_id'] = Auth::id();
		//$input['template_id'] = $request->template_id;
        $parametros = json_encode($input);
        $colunasbd = [];

        # Enviando $file e $parametros para método de leitura da planilha.
        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros, $tipo);
        /* Percorrendo campos retornados e enviando para a view onde o
            usuário escolhe as colunas que vão ser importadas e tipos.
        */
        if(is_array($retorno)){
            foreach ($retorno['colunas'] as $coluna => $type ) {
                $colunasbd[$coluna] = $coluna . ' - ' . $type;
            }

            # Colocando variaveis na sessão para fazer validações de campos obrigatórios.
            \Session::put('retorno', $retorno);
            \Session::put('colunasbd', $colunasbd);

            return redirect('/admin/levantamento/importar/selecionaCampos?planilha_id='.$retorno['planilha_id'].($template?'&template_id='.$template:''));
        }else{
            return $retorno;
        }
    }

    /**
     * Método para tranformar a requisição de POST para GET onde vamos fazer a validações dos campos obrigatórios
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selecionaCampos(Request $request){

        $retorno = $request->session()->get('retorno');
        $colunasbd = $request->session()->get('colunasbd');
        if($request->template_id){
            $planilha = Planilha::find($request->planilha_id);
            $template = TemplatePlanilha::find($request->template_id);
            if($planilha && $template) {
                $planilha->colunas_json = $template->colunas;
                $planilha->save();

                # Coloca processo na fila
                dispatch(new PlanilhaProcessa($planilha));

                # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
                Flash::warning('Importação incluída na FILA. Ao concluir o processamento enviaremos um ALERTA!');

                return redirect('admin/levantamento');
            }
        }
        return view('admin.levantamentos.checkIn', compact('retorno','colunasbd'));
    }

    /*
     * $request = Pegando os campos selecionado de colunas a ser importadas e tipos das colunas.
     * Método responsável por enviar os dados para o método da fila.
     */
    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode(array_filter($input));

        # Pegando todas as planilhas por ordem decrescente e que trás somente a ultima planilha importada pelo usuário
        $planilha = Planilha::where('user_id', \Auth::id())->orderBy('id','desc')->first();
        # Após encontrar a planilha, será feito um update adicionando em array os campos escolhido pelo usuário.
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->save();
        }

        # Salvar os campos escolhido na primeira importação de planilha para criar um modelo de template
        $template_orcamento = TemplatePlanilha::firstOrNew([
            'nome' => 'Levantamento',
            'modulo' => 'Levantamento'
        ]);
        $template_orcamento->colunas = $json;
        $template_orcamento->save();

        # Comentário de processamento de fila iniciada
        \Log::info("Ciclo de solicitações com filas iniciada");
        dispatch(new PlanilhaProcessa($planilha));
        # Comentário de processamento de fila finalizada
        \Log::info("Ciclo de solicitações com filas finalizada");

        # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
        Flash::warning('Importação incluída na FILA. Ao concluir o processamento enviaremos um ALERTA!');
        return redirect('admin/levantamento');
    }
	
	//Gráficos
	public function dashboard()
    {
        $reprovados = OrdemDeCompra::select([
            'ordem_de_compras.id',
            'obras.nome',
            'users.name'
        ])            ->join('obras', 'obras.id', 'ordem_de_compras.obra_id')
        ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
        ->where('oc_status_id', 4)->orderBy('id', 'desc')
        ->take(5)->get();

        $aprovados = OrdemDeCompra::select([
            'ordem_de_compras.id',
            'obras.nome',
            'users.name'
        ])            ->join('obras', 'obras.id', 'ordem_de_compras.obra_id')
        ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
        ->where('oc_status_id', 5)->orderBy('id', 'desc')
        ->take(5)->get();

        $emaprovacao = OrdemDeCompra::select([
            'ordem_de_compras.id',
            'obras.nome',
            'users.name'
        ])            ->join('obras', 'obras.id', 'ordem_de_compras.obra_id')
        ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
        ->where('oc_status_id', 3)->orderBy('id', 'desc')
        ->take(5)->get();

        $ordemDeCompras = OrdemDeCompra::select([
                'ordem_de_compras.id',
                'obras.nome as obra',
                'users.name as usuario',
                'oc_status.nome as situacao',
                'ordem_de_compras.obra_id'
            ])
            ->join('obras', 'obras.id', '=', 'ordem_de_compras.obra_id')
            ->join('oc_status', 'oc_status.id', '=', 'ordem_de_compras.oc_status_id')
            ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
            ->whereRaw('EXISTS (SELECT 1 FROM obra_users WHERE obra_users.obra_id = obras.id AND user_id=?)', auth()->id())
            ->where('ordem_de_compras.oc_status_id', '!=', 6)
            ->orderBy('ordem_de_compras.id','DESC')
            ->get();

        $dentro_orcamento = 0;
        $acima_orcamento = 0;

        if(count($ordemDeCompras)) {
            foreach ($ordemDeCompras as $ordemDeCompra) {
                $saldoDisponivel = OrdemDeCompraRepository::saldoDisponivel($ordemDeCompra->id, $ordemDeCompra->obra_id);
                if($saldoDisponivel >= 0) {
                    $dentro_orcamento += 1;
                } else {
                    $acima_orcamento += 1;
                }
            }
        }
        
        return view('admin.levantamentos.dashboard', compact('reprovados', 'aprovados', 'emaprovacao', 'abaixo_orcamento', 'dentro_orcamento', 'acima_orcamento'));
    }


}
