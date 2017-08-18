<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CronogramaFisicoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CronogramaFisicoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\Obra;
use App\Models\Planilha;
use App\Models\TemplatePlanilha;
use App\Models\TipoOrcamento;
use App\Repositories\Admin\CronogramaFisicoRepository;
use App\Repositories\Admin\SpreadsheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

class CronogramaFisicoController extends AppBaseController
{
    /** @var  CronogramaFisicoRepository */
    private $orcamentoRepository;

    public function __construct(CronogramaFisicoRepository $cronogramaRepo)
    {
        $this->cronogramaFisicoRepository = $cronogramaRepo;
    }

    /**
     * Display a listing of the Cronograma.
     *
     * @param OrcamentoDataTable $orcamentoDataTable
     * @return Response
     */
    public function index(CronogramaFisicoDataTable $cronogramaFisicoDataTable)
    {
        return $cronogramaFisicoDataTable->render('admin.cronograma_fisicos.index');
    }

    /**
     * Show the form for creating a new Cronograma.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.cronograma_fisicos.create');
    }

    /**
     * Store a newly created Cronograma in storage.
     *
     * @param CreateOrcamentoRequest $request
     *
     * @return Response
     */
    public function store(CreateCronogramaFisicoRequest $request)
    {
        $input = $request->all();

        $cronograma = $this->cronogramaFisicoRepository->create($input);

        Flash::success('Cronograma '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Display the specified Cronograma.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cronograma = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronograma)) {
            Flash::error('Cronograma '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        return view('admin.cronograma_fisicos.show')->with('cronograma', $cronograma);
    }

    /**
     * Show the form for editing the specified Cronograma.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cronograma = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronograma)) {
            Flash::error('Cronograma '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        return view('admin.cronograma_fisicos.edit')->with('cronograma', $cronograma);
    }

    /**
     * Update the specified Cronograma in storage.
     *
     * @param  int              $id
     * @param UpdateCronogramFisicoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCronogramFisicoRequest $request)
    {
        $cronograma = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronograma)) {
            Flash::error('Cronograma '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        $cronograma = $this->cronogramaFisicoRepository->update($request->all(), $id);

        Flash::success('Cronograma '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Remove the specified Cronograma from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cronograma = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronograma)) {
            Flash::error('Cronograma '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        $this->cronogramaFisicoRepository->delete($id);

        Flash::success('Cronograma '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    ################################ IMPORTAÇÃO ###################################

    /**
     * $obras = Buscando chave e valor para fazer o combobox da view
     * $orcamento_tipos = Buscando chave e valor para fazer o combobox da view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexImport(){
        $obras = Obra::pluck('nome','id')->toArray();
		//$cronograma_fisico_tipos = CronogramaFisicoTipos::pluck('nome','id')->toArray();
        $orcamento_tipos = TipoOrcamento::pluck('nome','id')->toArray();
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Físicos')->pluck('nome','id')->toArray();
        //return view('admin.cronograma_fisicos.indexImport', compact('cronograma_fisico_tipos','obras', 'templates'));
		return view('admin.cronograma_fisicos.indexImport', compact('orcamento_tipos','obras', 'templates'));
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
        $tipo = 'orcamento';
        $file = $request->except('obra_id','template_id','orcamento_tipo_id');
        $input = $request->except('_token','file');
        $this->validate($request,['file'=>'file','obra_id'=>'required']);
        $template = $request->template_id;
        $obra_id = null;
        $orcamento_tipo_id = null;

        # Validando campos obrigatórios como chave estrangeiras
        if($input['obra_id'] != "") {
            $obra_id = array_key_exists('obra_id', $input);
        }
        if($input['orcamento_tipo_id'] != "") {
            $orcamento_tipo_id = array_key_exists('orcamento_tipo_id', $input);
        }
        if(!$obra_id || !$orcamento_tipo_id){
            Flash::error('Os campos: obra e tipo orçamento são obrigátorios.');
            return back();
        }


        $input['user_id'] = Auth::id();
        $parametros = json_encode($input);
        $colunasbd = [];

        # Enviando $file e $parametros para método de leitura da planilha.
        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros, $tipo);

        /* Percorrendo campos retornados e enviando para a view onde o
            usuário escolhe as colunas que vão ser importadas e tipos.
        */
        foreach ($retorno['colunas'] as $coluna => $type ) {
            $colunasbd[$coluna] = $coluna . ' - ' . $type;
        }

        # Colocando variaveis na sessão para fazer validações de campos obrigatórios.
        \Session::put('retorno', $retorno);
        \Session::put('colunasbd', $colunasbd);

        return redirect('/admin/orcamento/importar/selecionaCampos?planilha_id='.$retorno['planilha_id'].($template?'&template_id='.$template:''));
    }

    /**
     * Método para tranformar a requisição de POST para GET onde vamos fazer a validações dos campos obrigatórios
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selecionaCampos(Request $request)
    {
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

                return redirect('admin/orcamento');
            }
        }
        return view('admin.orcamentos.checkIn', compact('retorno','colunasbd'));
    }

    /*
     * $request = Pegando os campos selecionado de colunas a ser importadas e tipos das colunas.
     * Método responsável por enviar os dados para o método da fila.
     */
    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode(array_filter($input));
//        dd($json);
        # Validando campos obrigatórios como chave estrangeiras
        $codigo_insumo = in_array('codigo_insumo', $input);
        $unidade_sigla = in_array('unidade_sigla', $input);
        $descricao = in_array('descricao', $input);
        if(!$codigo_insumo || !$unidade_sigla || !$descricao){
            Flash::error('Os campos: codigo_insumo, unidade_sigla e descrição são obrigátorios.');
            return back();
        }

        # Pegando todas as planilhas por ordem decrescente e que trás somente a ultima planilha importada pelo usuário
        $planilha = Planilha::where('user_id', \Auth::id())->orderBy('id','desc')->first();
        # Após encontrar a planilha, será feito um update adicionando em array os campos escolhido pelo usuário.
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->save();
        }

        # Salvar os campos escolhido na primeira importação de planilha para criar um modelo de template
        $parametros_json = json_decode($planilha->parametros_json);
        $orcamento = TipoOrcamento::find(intval($parametros_json->orcamento_tipo_id));
        $template_orcamento = TemplatePlanilha::firstOrNew([
            'nome' => $orcamento->nome,
            'modulo' => 'Orçamento'
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
        return redirect('admin/orcamento');
    }
}
