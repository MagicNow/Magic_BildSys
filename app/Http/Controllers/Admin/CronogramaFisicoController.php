<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CronogramaFisicoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CronogramaFisicoRequest;
use App\Http\Requests\Admin\UpdateCronogramaFisicoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\OrdemDeCompra;

use App\Repositories\OrdemDeCompraRepository;
use App\Repositories\Admin\ObraRepository;
use App\Models\CronogramaFisico;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\PlanejamentoCompra;
use App\Models\Planilha;
use App\Models\Servico;
use App\Models\TemplatePlanilha;
use App\Repositories\Admin\CronogramaFisicoRepository;
use App\Repositories\Admin\SpreadsheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class CronogramaFisicoController extends AppBaseController
{
    /** @var  CronogramaFisicoRepository */
    private $cronogramaFisicoRepository;

    public function __construct(CronogramaFisicoRepository $cronogramaFisicoRepo)
    {
        $this->cronogramaFisicoRepository = $cronogramaFisicoRepo;
    }

    /**
     * Display a listing of the Cronograma Fisico.
     *
     * @param CronogramaFisicoDataTable $cronogramaFisicoDataTable
     * @return Response
     */
    public function index(Request $request, CronogramaFisicoDataTable $cronogramaFisicoDataTable)
    {
        $id = null;
        if($request->id){
            $id = $request->id;
        }
				
		$obras = Obra::pluck('nome','id')->toArray();
		/*$tipos = ["", "", "", "", ""];
		$ano = ["", "", "", "", ""];
		$mes = [];*/
		
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Fisicos')->pluck('nome','id')->toArray();
		
        return $cronogramaFisicoDataTable->porObra($id)->render('admin.cronograma_fisicos.index', compact('obras','templates','id'));
    }

    /**
     * Show the form for creating a new Cronograma Fisico.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('admin.cronograma_fisicos.create', compact('obras'));
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

        $cronogramaFisico = $this->cronogramaFisicoRepository->create($input);

        Flash::success('Cronograma Fisico '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Display the specified Cronograma Fisico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);        

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        return view('admin.cronograma_fisicos.show', compact('cronogramaFisico'));
    }

    /**
     * Show the form for editing the specified Cronograma Fisico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $obras = Obra::pluck('nome','id')->toArray();
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }


        return view('admin.cronograma_fisicos.edit', compact('cronogramaFisico','obras'));
    }

    /**
     * Update the specified Cronograma Fisico in storage.
     *
     * @param  int              $id
     * @param UpdatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCronogramaFisicoRequest $request)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }
        $input = $request->all();

        $cronogramaFisico = $this->cronogramaFisicoRepository->update($input, $id);

        Flash::success('Cronograma Fisico '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Remove the specified Cronograma Fisico from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        $this->cronogramaFisicoRepository->delete($id);

        Flash::success('Cronograma Fisico '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
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
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Fisicos')->pluck('nome','id')->toArray();
        return view('admin.cronograma_fisicos.indexImport', compact('obras','templates','id'));
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
        $tipo = 'cronogramaFisico';
        $file = $request->except('obra_id','template_id');
        $input = $request->except('_token','file');
        $template = $request->template_id;
        $input['user_id'] = Auth::id();
		$input['template_id'] = $request->template_id;
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

            return redirect('/admin/cronogramaFisico/importar/selecionaCampos?planilha_id='.$retorno['planilha_id'].($template?'&template_id='.$template:''));
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

                return redirect('admin/cronogramaFisico');
            }
        }
        return view('admin.cronograma_fisicos.checkIn', compact('retorno','colunasbd'));
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
            'nome' => 'CronogramaFisico',
            'modulo' => 'CronogramaFisico'
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
        return redirect('admin/cronogramaFisico');
    }
	
	//Acompanhamento Semanal
	public function relSemanal(Request $request)
    {		
		
		/*if(!is_null($request->days)) {
            $query->whereDate(
                'contratos.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }*/
		
		//Filtros
		$dayMonth =  new Carbon('first day of this month');
		$endMonth =  new Carbon('last day of this month');
		
        if($request->data_start) {            
            $fromDate = Carbon::createFromFormat('d-m-Y', $request->data_start)->toDateString();                            
        }else{
			$fromDate = $dayMonth->toDateString();    
		}

        if($request->data_end) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->data_end)->toDateString();
        }else{			
			$endDate = $endMonth->toDateString();   
		}

		if($request->semana_id) {            
            $semanaDate = $request->semana_id;
        }else{
			$semanaDate = "2017-09-01";
		}
		
		if($request->obra_id) {            
            $obraId = $request->obra_id;
        }else{
			$obraId = 0;
		}
		
		$fridays = CronogramaFisicoRepository::getFridaysBydate($fromDate);		
		$last_day= end($fridays);			

        $obras = Obra::pluck('nome','id')->toArray();        

		//Tabela Percentual Previsto e Acumulado		
		$tabelaPercPrevistoRealizadosSemanas = $fridays;
		
		$tabelaPercPrevistoRealizados = CronogramaFisico::select([
			'cronograma_fisicos.id',                
			'template_planilhas.nome as tipo',				                												
				DB::raw("(CONCAT(cronograma_fisicos.concluida,'%')
					) as concluida"
			),                 
			'cronograma_fisicos.data_inicio'		
        ])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')		
		->whereDate('cronograma_fisicos.data_inicio','>=',Carbon::createFromFormat('Y-m-d', $semanaDate)->toDateString())
		->where('cronograma_fisicos.obra_id', $obraId)
        ->orderBy('id', 'desc')
		->take(7)
		->get();	

		//Tabela Tarefas Criticas
		$tabelaTarefasCriticasTitulos = ["LOCAL","Tarefas Críticas","Previsto Ac.","Realizado Ac.","Desvio"];
		
		$tabelaTarefasCriticasDados= CronogramaFisico::select([
			'cronograma_fisicos.id',
			'cronograma_fisicos.torre as local',
			'cronograma_fisicos.tarefa',
			DB::raw("(SELECT (case when count(distinct CF.id) = 1 then 'Sim' else 'Não' end) as tarefa_mes
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id
						AND (CF.data_inicio >= '$fromDate' AND CF.data_inicio < '$last_day')
						AND (CF.data_termino>= '$fromDate' AND CF.data_termino< '$last_day')
					 ) as tarefa_mes"
			),
			DB::raw("(SELECT   
						CASE    
							WHEN (CF.data_inicio >= '$fridays[0]') THEN '0%' 
							WHEN (CF.data_termino <= '$fridays[0]') THEN '100%' 								
						END AS semana1
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id 
					 ) as previsto_acumulado"
			),
			DB::raw("(SELECT   
						CASE    
							WHEN (1) THEN '0%' 								
						END AS semana1
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id 
					 ) as realizado_acumulado"
			)
			
        ])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')				
		->where('cronograma_fisicos.resumo','=','Não')
		//->where filtro obra, ano, mes e semana
		->where('cronograma_fisicos.obra_id', $obraId)
		->whereDate('cronograma_fisicos.data_inicio','>=',Carbon::createFromFormat('Y-m-d', $fromDate)->toDateString())
        ->orderBy('id', 'desc')		
		->get();
               
        return view('admin.cronograma_fisicos.relSemanal', compact('obras','fridays', 'tabelaPercPrevistoRealizadosSemanas',  'tabelaPercPrevistoRealizados', 'tabelaTarefasCriticasTitulos', 'tabelaTarefasCriticasDados'));
    }

	public function relMensal(Request $request)
    {	
		
		$obras = Obra::pluck('nome','id')->toArray(); 	
		
		$assertividadeMensal = [0.68, 0.68, 0.97, 0.71, -26.80];
               
        return view('admin.cronograma_fisicos.relMensal', compact('obras', 'assertividadeMensal'));
    }
	
	

}
