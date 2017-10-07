<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CronogramaFisicoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CronogramaFisicoRequest;
use App\Http\Requests\Admin\UpdateCronogramaFisicoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\CronogramaFisico;
use App\Models\Obra;
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
				
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Fisicos')->pluck('nome','id')->toArray();
		
        return $cronogramaFisicoDataTable->porObra($id)->render('admin.cronograma_fisicos.index', compact('obras', 'templates','id'));
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
	
	################################ Relatórios ###################################
	
	//Acompanhamento Semanal
	public function relSemanal(Request $request)
    {		
					
		//Carregar Combos
        $obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                        
                        ->orderBy('obras.nome', 'ASC')
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();
						
		$semanas = ["1","2","3","4","5"];

		$meses = [];
				
		return view('admin.cronograma_fisicos.relSemanal', compact('obras', 'semanas', 'meses'));
    }
	
	public function semanalCarregarTabelas(Request $request){
				
		//Carregar Dados Iniciais	
		$tabColetaSemanal = [];
		$tabColetaSemanal['labels1'] = [];
		$tabColetaSemanal['labels2'] = [];
		$tabColetaSemanal['data'] = [];
		
		$tabPercentualPrevReal = [];		
		$tabPercentualPrevReal['labels'] = [];
		$tabPercentualPrevReal['data']['planoDiretorAcumulado'] = [];
		$tabPercentualPrevReal['data']['planoTrabalhoAcumulado'] = [];
		$tabPercentualPrevReal['data']['planoRealizadoAcumulado'] = [];
		$tabPercentualPrevReal['data']['planoPrevistoAcumulado'] = [];
		$tabPercentualPrevReal['data']['previstoSemanal'] = [];	
		$tabPercentualPrevReal['data']['realizadoSemanal'] = [];			
				
		$tabTarefasCriticas = [];
		$tabTarefasCriticas['labels'] = [];
		$tabTarefasCriticas['data'] = [];		
		
		//Filtros Obra, Mês e Semana de Referências		
		if($request->obra_id != "") {            
            
			$obraId = $request->obra_id;
			
			$obraDataInicio = Obra::select([
							'obras.id',
							'obras.data_inicio'							
						])											
						->where('obras.id', $obraId)						
                        ->orderBy('obras.id', 'ASC')                        
                        ->first()
						->data_inicio;	
								
			$obraDataInicio = Carbon::parse($obraDataInicio);
			
			if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
				$obraDataFinal = Carbon::now();
			}else{
				$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
			}
			
			$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
			$mesesObra = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, Carbon::parse($obraDataInicio)->addMonths(36)); // Todos os meses da Obra
			
		}		
						
		if($request->mes_id != "") { 
			
			$mesId = $request->mes_id;
			$mesRef = $meses[$mesId];
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->startOfMonth();
			$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->endOfMonth();
						
		}
			
		if($request->semana_id != "") { 
						
			$semanaId = $request->semana_id;
		}
						
		//Retornar as Regras
		if(($request->obra_id != "")&&($request->mes_id != "")&&($request->semana_id != "")){
					
			$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);
			
			/***** Tabela Coleta Semanal - Dados, pega da Tendencia Real ****/
			$tabColetaSemanal['data'] = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, "Tendência Real");		
			$tabColetaSemanal['labels1'] = $sextasArray;	; //Label Horizontal
			$tabColetaSemanal['labels2'] = ["Local", "Pavimento", "Tarefas", "Início Real" , "Término Real" , "Crítica" , "Peso Total" , "Real Acum. Mês Anterior" , "Previsto" , "Realizado" , "Previsto" , "Realizado" , "Previsto" , "Realizado" , "Previsto" , "Realizado" , "Previsto" , "Realizado" , "Farol"];			
						
			/***** Tabela Percentual Previsto e Acumulado ****/	
			$tabPercentualPrevReal['labels'] = CronogramaFisicoRepository::getFridaysByDate($inicioMes);	; //Label Horizontal				

			/***** Tabela Percentual Previsto x Percentual Realizado - Dados: Vindo da Curva de Andamento (PD, PT, TR, TD e TT) ****/			
			$planoDiretorAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Plano Diretor"),"percentual");
			$planoTrabalhoAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Plano Trabalho"),"percentual");
			$planoPrevistoAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Tendência Real"),"percentual");	
			$planoRealizadoAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Tendência Real"),"realizado");
			
			$tabPercentualPrevReal['data']['planoDiretorAcumulado'] = $this->planoAcumuladoMensal($planoDiretorAcumulado, $mesRef);			
			$tabPercentualPrevReal['data']['planoTrabalhoAcumulado'] = $this->planoAcumuladoMensal($planoTrabalhoAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['planoPrevistoAcumulado'] = $this->planoAcumuladoMensal($planoPrevistoAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['planoRealizadoAcumulado'] = $this->planoAcumuladoMensal($planoRealizadoAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['previstoSemanal'] = $this->previstoSemanal($tabColetaSemanal['data'], $inicioMes);
			$tabPercentualPrevReal['data']['realizadoSemanal'] = $this->realizadoSemanal($tabColetaSemanal['data'], $inicioMes);
						
			$tmpRealizado = $tabPercentualPrevReal['data']['realizadoSemanal'];
			$tmpPrevisto = $tabPercentualPrevReal['data']['previstoSemanal'];
			
			$subDesvio = array_map(function ($x, $y) { return $x-$y; } , $tmpRealizado, $tmpPrevisto);
			$desvioSemanal     = array_combine(array_keys($tmpRealizado), $subDesvio);
			
			$tabPercentualPrevReal['data']['desvioSemanal'] = $desvioSemanal;			
			
			/***** Tabela Tarefas Críticas ****/
			$tabTarefasCriticas = [];
			$tabTarefasCriticas['labels'] = ["LOCAL","Tarefas Críticas","Previsto Ac.","Realizado Ac.","Desvio"]; //Label 		
			$tabTarefasCriticas['data'] = $this->tabTarefasCriticas($tabColetaSemanal['data'], $inicioMes, $semanaId); //Dados
												
		}
		
		return view('admin.cronograma_fisicos.relSemanalTabelas',compact('tabPercentualPrevReal', 'tabTarefasCriticas', 'tabColetaSemanal'));
		
	}
	
	public function semanalCarregarGraficos(Request $request){
				
		//Carregar Dados Iniciais	
		$tabColetaSemanal = [];
		$tabColetaSemanal['data'] = [];
				
		$grafPrevistoRealizadoSem = [];
		$grafPrevistoRealizadoSem['labels'] = [];
		$grafPrevistoRealizadoSem['data'] = [];
		
		$grafPDPTrabRealAcumSem = [];
		$grafPDPTrabRealAcumSem['labels'] = [];
		$grafPDPTrabRealAcumSem['data'] = [];
		
		$tabTarefasCriticas = [];		
		$tabTarefasCriticas['data'] = [];
		
		$grafTarefasCriticas = [];
		$grafTarefasCriticas['labels'] = [];
		$grafTarefasCriticas['data']['previstoAcum'] = [];
		$grafTarefasCriticas['data']['realizadoAcum'] = [];
		
		//Filtros Obra, Mês e Semana de Referências		
		if($request->obra_id != "") {            
            
			$obraId = $request->obra_id;
			
			$obraDataInicio = Obra::select([
							'obras.id',
							'obras.data_inicio'							
						])											
						->where('obras.id', $obraId)						
                        ->orderBy('obras.id', 'ASC')                        
                        ->first()
						->data_inicio;	
								
			$obraDataInicio = Carbon::parse($obraDataInicio);
			
			if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
				$obraDataFinal = Carbon::now()->subMonth();
			}else{
				$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
			}
			
			$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
			$mesesObra = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, Carbon::parse($obraDataInicio)->addMonths(36)); // Todos os meses da Obra
			
		}		
						
		if($request->mes_id != "") {            
			
			$mesId = $request->mes_id;
			$mesRef = $meses[$mesId];
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->startOfMonth();
			$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->endOfMonth();	
			
		}
			
		if($request->semana_id != "") { 
			$semanaId = $request->semana_id;
			$semanaText = $semanaId + 1;
		}
				
		
		//Retornar as Regras
		if(($request->obra_id != "")&&($request->mes_id != "")&&($request->semana_id != "")){
					
			$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);
			
			/***** Tabela Coleta Semanal - Dados, pega da Tendencia Real ****/
			$tabColetaSemanal['data'] = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, "Tendência Real");					
			
			/***** Gráfico Previsto x Realizado na Semana selecionada	 ****/	
			$grafPrevistoRealizadoSem['labels'] = ["Semana 1", "Semana 2", "Semana 3", "Semana 4", "Semana 5", "Mês"];
			$grafPrevistoRealizadoSem['data']['previstoSem'] = $this->previstoSemanal($tabColetaSemanal['data'], $inicioMes);
			$grafPrevistoRealizadoSem['data']['realizadoSem'] = $this->realizadoSemanal($tabColetaSemanal['data'], $inicioMes);	

			/***** Gráfico PDP x Trab x Real Acumulado na Semana selecionada *****/
			$grafPDPTrabRealAcumSem['labels'] = ["Semana ".$semanaText];
			
			$planoDiretorAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Plano Diretor"),"percentual");
			$planoDiretorAcumulado = $this->planoAcumuladoMensal($planoDiretorAcumulado, $mesRef);
			
			$planoTrabalhoAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Plano Trabalho"),"percentual");
			$planoTrabalhoAcumulado = $this->planoAcumuladoMensal($planoTrabalhoAcumulado, $mesRef);
			
			$planoRealizadoAcumulado = $this->planoAcumuladoMeses($this->percentualPorMeses($obraId, $meses, "Tendência Real"),"realizado");
			$planoRealizadoAcumulado = $this->planoAcumuladoMensal($planoRealizadoAcumulado, $mesRef);
			
			array_push($grafPDPTrabRealAcumSem['data'], $planoDiretorAcumulado[$sextasArray[$semanaId]]);
			array_push($grafPDPTrabRealAcumSem['data'], $planoTrabalhoAcumulado[$sextasArray[$semanaId]]);
			array_push($grafPDPTrabRealAcumSem['data'], $planoRealizadoAcumulado[$sextasArray[$semanaId]]);
			
			/***** Gráfico Desvio PDP e Desvio Trab na Semana selecionada *****/
			$graficoDesvioPDP = $planoRealizadoAcumulado[$sextasArray[$semanaId]] - $planoDiretorAcumulado[$sextasArray[$semanaId]];
			$graficoDesvioPTrab = $planoRealizadoAcumulado[$sextasArray[$semanaId]] - $planoTrabalhoAcumulado[$sextasArray[$semanaId]];			
			
			/***** Gráfico Tarefas Críticas	 ****/	
			$tabTarefasCriticas['data'] = $this->tabTarefasCriticas($tabColetaSemanal['data'], $inicioMes, $semanaId); //Dados									
			
			$grafTarefasCriticasLabels = [];
			$grafTarefasCriticasPrevAcum = [];
			$grafTarefasCriticasRealAcum = [];
						
			foreach ($tabTarefasCriticas['data'] as $tmp1) {			
				array_push($grafTarefasCriticasLabels, ltrim($tmp1['tarefa']));							
			}
			$grafTarefasCriticas['labels'] = $grafTarefasCriticasLabels;		
			
			foreach ($tabTarefasCriticas['data'] as $tmp2) {			
				array_push($grafTarefasCriticasPrevAcum, $tmp2['previsto']);
				array_push($grafTarefasCriticasRealAcum, $tmp2['realizado']);			
			}		
			
			$grafTarefasCriticas['data']['previstoAcum'] = $grafTarefasCriticasPrevAcum;
			$grafTarefasCriticas['data']['realizadoAcum'] = $grafTarefasCriticasRealAcum;	
		}
		
		//Salvar dados no array para passar para a view relSemanal
		$data = [];
		$data['grafPrevistoRealizadoSem'] = $grafPrevistoRealizadoSem;
		$data['grafPDPTrabRealAcumSem'] = $grafPDPTrabRealAcumSem;
		$data['grafTarefasCriticas'] = $grafTarefasCriticas;
		$data['graficoDesvioPDP'] = $graficoDesvioPDP;
		$data['graficoDesvioPTrab'] = $graficoDesvioPTrab;

		return $data;
			
		
	}
	
	// Retornar Valor % por Tipo de Planejamento, Obra, Mês e Semana
	public function tabColetaSemanal($obraId, $inicioMes, $fimMes, $tipoPlanejamento){	
				
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$ultimaSexta= end($sextasArray);
		
		$tabColetaSemanal = CronogramaFisico::select([
			'cronograma_fisicos.id',
			'cronograma_fisicos.torre',
			'cronograma_fisicos.pavimento',				
			'cronograma_fisicos.tarefa',
			'cronograma_fisicos.data_inicio',
			'cronograma_fisicos.data_termino',
			'cronograma_fisicos.critica',
			DB::raw("(SELECT ROUND(CF.custo/
						(SELECT custo 
							FROM cronograma_fisicos  
							WHERE tarefa like '%Cronograma%'
							GROUP BY custo
						)*100,2) custo_total
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id 
					 ) as peso"
			),
			'cronograma_fisicos.concluida',							
			/*DB::raw("(SELECT (case when count(distinct CF.id) = 1 then 'Sim' else 'Não' end) as tarefa_mes
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id						
						AND (CF.data_termino >= '$inicioMes')						
						AND (CF.data_inicio <= '$fimMes')
					 ) as tarefa_mes"
			),*/					
			/*DB::raw("(SELECT MF.valor_medido as valor_realizado
						FROM medicao_fisicas MF
						WHERE MF.tarefa = cronograma_fisicos.tarefa						
						AND MF.obra_id = cronograma_fisicos.obra_id						
					 ) as valor_realizado"
			),*/
			'cronograma_fisicos.created_at'					
		])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')
		->where('cronograma_fisicos.obra_id', $obraId)
		->where('cronograma_fisicos.resumo','Não')
		->where('cronograma_fisicos.data_termino','>=', $inicioMes)
		->where('cronograma_fisicos.data_inicio','<=', $fimMes)		
		->where('template_planilhas.nome', $tipoPlanejamento)		
		->orderBy('cronograma_fisicos.data_inicio', 'desc')
		->groupBy('cronograma_fisicos.tarefa')		
		->get()
		->toArray();
				
		//Filtrar por Tarefa do Mês				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {				
			
			//if($tarefa['tarefa_mes']!='Sim'){
				
				//unset($tabColetaSemanal[$keyT]);
			
			//}else{
				
				//echo $tarefa['tarefa'];
				
				foreach($sextasArray as $sexta){								
					
					//Calcular % da Semana
					$inicioTarefa = Carbon::parse($tarefa['data_inicio']);					
					$inicioTarefa = $inicioTarefa->format('d/m/Y');
					$fimTarefa = Carbon::parse($tarefa['data_termino']);
					$fimTarefa = $fimTarefa->format('d/m/Y');
					
					$inicioSemana = Carbon::createFromFormat("d/m/Y", $sexta);				
					$inicioSemana = $inicioSemana->format('d/m/Y');
					
					$inicioRealSemana = Carbon::createFromFormat("d/m/Y", $sexta)->subDays(6);				
					$inicioRealSemana = $inicioRealSemana->format('d/m/Y');
					
					$fimSemana = Carbon::createFromFormat("d/m/Y", $sexta);	
					$fimSemana = $fimSemana->format('d/m/Y');
							

					$valorPrevisto = CronogramaFisicoRepository::getPrevistoPorcentagem($inicioTarefa, $fimTarefa, $inicioSemana);	
					$valorMedicaoFisica = CronogramaFisicoRepository::getRealizadoPorcentagem($inicioRealSemana, $fimSemana, $obraId, $tarefa['tarefa']);																				
					
					$tabColetaSemanal[$keyT]["percentual-".$sexta] =  round($valorPrevisto,2);
					$tabColetaSemanal[$keyT]["realizado-".$sexta] =  round($valorMedicaoFisica,2); //Mediçao Fisicas
					//Mes
					//Farol
										
				}	
			//}	
		}
		
		return $tabColetaSemanal;
	}
		
	// Dados calculados em todos os meses
	public function percentualPorMeses($obraId, $meses, $tipoPlanejamento){		
		
		foreach ($meses as $mes) {
												
			if (isset($mes)){			
				
				$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->startOfMonth();
				$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->endOfMonth();
				
				$percentualPorMes[$mes] = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, $tipoPlanejamento);												
			}
			
		}
		
		return $percentualPorMes;
		
	}

	// Dados vindo calculados dos plano escolhido e mostrado suas porcentagens
	public function planoPorMeses($percentualPorMeses, $tipoDados){
				
		$planoPorMeses = [];
		
		foreach ($percentualPorMeses as $mes => $dados) {			
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->startOfMonth();
			
			// Todas as Sextas do Mês de Referencia
			$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);

			foreach ($sextasArray as $sexta) {
					
					$acumuladoSemanal = 0;
					
					foreach ($dados as $tmp) {
						$acumuladoSemanal = $acumuladoSemanal + ($tmp[$tipoDados."-".$sexta]/100) * $tmp["peso"];						
					}				
					
					$planoPorMeses[$mes][$sexta] = round($acumuladoSemanal,2);
			}
			
			$planoPorMeses[$mes]['mes'] = round($acumuladoSemanal,2);
			
									
		}	
				
		return $planoPorMeses;
		
		
	}	
	
	// Dados vindo calculados dos plano escolhido
	public function planoAcumuladoMeses($percentualPorMeses, $tipoDados){
				
		$planoAcumulado = [];
		$acumuladoTotal = 0;
		
		foreach ($percentualPorMeses as $mes => $dados) {			
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->startOfMonth();
			
			// Todas as Sextas do Mês de Referencia
			$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);

			foreach ($sextasArray as $sexta) {
					
					$acumuladoSemanal = 0;
					
					foreach ($dados as $tmp) {
						$acumuladoSemanal = $acumuladoSemanal + ($tmp[$tipoDados."-".$sexta]/100) * $tmp["peso"];						
					}				
					
					$acumuladoTotal = $acumuladoTotal  + $acumuladoSemanal;
					$planoAcumulado[$mes][$sexta] = round($acumuladoTotal,2);
			}
			
			$planoAcumulado[$mes]['mes-acumulado'] = round($acumuladoTotal,2);
			
									
		}	
				
		return $planoAcumulado;
		
		
	}
	
	// Filtrar dados do plano Acumulados para o Mes de Referencia
	public function planoAcumuladoMensal($planoAcumuladoMeses, $mesRef){
				
		$planoAcumuladoMensal = $planoAcumuladoMeses[$mesRef];						
				
		return $planoAcumuladoMensal;
		
	}	
		
	// Retornar dados do valor Previsto por Semana	
	public function previstoSemanal($tabColetaSemanal, $inicioMes){
				
		//previsto semanal = (previsto da semana - concluida da tarefa) * peso tarefa
				
		$previstoSemanal = [];
		$acumuladoMes = 0;
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$semanaId = 0;
		$valorPrevistoAnterior = 0;
		
		foreach ($sextasArray as $sexta) {
						
			$acumuladoSemanal = 0;
			$valorPrevisto = 0;
			$valorConcluida = 0;
			$valorPeso = 0;				
			
			//Filtrar por Critica				
			foreach ($tabColetaSemanal as $key => $tarefa) {
				
				if(isset($tarefa["percentual-".$sexta]) && isset($tarefa["concluida"]) && isset($tarefa["peso"]) ){
					$valorPrevisto = $tarefa["percentual-".$sexta];
					$valorConcluida = $tarefa["concluida"];
					$valorPeso = $tarefa['peso'];
				}
				
				//Senao for semana 1 pegar o atribuir valor concluida com o valor previsto da semana anterior
				if($semanaId >0){
					$valorConcluida = $tarefa["percentual-".$semanaAnterior];
				}
				
				$acumuladoSemanal = $acumuladoSemanal + (($valorPrevisto - $valorConcluida)/100)*$valorPeso;									
					
			}
			
			$previstoSemanal[$sexta] = round($acumuladoSemanal,2);
			$acumuladoMes = $acumuladoMes + round($acumuladoSemanal,2);
			
			$semanaId = $semanaId + 1 ;
			$semanaAnterior	= $sexta;
		}		
		
		$previstoSemanal["mes"] = $acumuladoMes; 
						
		return $previstoSemanal;
	}
			
	// Dados vindo do Mediçao Fisicas
	public function realizadoSemanal($tabColetaSemanal, $inicioMes){
								
		$realizadoSemanal = [];
		$acumuladoMes = 0;
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		
		foreach ($sextasArray as $sexta) {
			
			$acumuladoSemanal = 0;
			$valorRealizado = 0;
			$valorConcluida = 0;
			$valorPeso = 0;
				
			//Filtrar por Critica				
			foreach ($tabColetaSemanal as $key => $tarefa) {
				
				if(isset($tarefa["realizado-".$sexta]) && isset($tarefa["concluida"]) && isset($tarefa["peso"]) ){
					$valorRealizado = $tarefa["realizado-".$sexta];
					$valorConcluida = $tarefa["concluida"];
					$valorPeso = $tarefa['peso'];
				}
				
				// Se nao teve nada realizado tem que zerar o acumuladado
				if($valorRealizado > 0){
					$acumuladoSemanal = $acumuladoSemanal + (($valorRealizado - $valorConcluida)/100)*$valorPeso;								
				}					
			}
			
			$realizadoSemanal[$sexta] = round($acumuladoSemanal,2);
			$acumuladoMes = $acumuladoMes + round($acumuladoSemanal,2);
		}		
		
		$realizadoSemanal["mes"] = $acumuladoMes; 
				
		
		return $realizadoSemanal;	
	}	
	
	// Tabela Tarefas Criticas - Dados
	public function tabTarefasCriticas($tabColetaSemanal, $inicioMes, $semanaId){
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$ultimaSexta= end($sextasArray);
		
		//Data da Sexta feira da Semana de Referência
		$semanaSexta = $sextasArray[$semanaId];
		
		$tabTarefasCriticas = [];
				
		//Filtrar por Critica				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {
			
			$tmp1="percentual-".$semanaSexta;
			$tmp2="realizado-".$semanaSexta;
					
			if($tarefa['critica']=='Sim'){					
				
				$tabTarefasCriticas[$keyT]['local'] = $tarefa['torre'];
				$tabTarefasCriticas[$keyT]['tarefa'] = ltrim($tarefa['tarefa']);
				$tabTarefasCriticas[$keyT]['peso'] = $tarefa['peso'];
				$tabTarefasCriticas[$keyT]['previsto'] = $tarefa[$tmp1];
				$tabTarefasCriticas[$keyT]['realizado'] = $tarefa[$tmp2];
			
			}			
				
		}		
		
		return $tabTarefasCriticas;
	}
	
	// Carregar os meses de acordo com a Obra
	public function mesesPorObra(Request $request){	
		
		$obraId = $request->obra_id;
			
		$obraDataInicio = Obra::select([
						'obras.id',
						'obras.data_inicio'							
					])											
					->where('obras.id', $obraId)						
					->orderBy('obras.id', 'ASC')                        
					->first()
					->data_inicio;	
							
		$obraDataInicio = Carbon::parse($obraDataInicio);
		
		if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
			$obraDataFinal = Carbon::now();
		}else{
			$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
		}
		
		$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
		
		return $meses;
    }
		
	//Acompanhamento Mensal
	public function relMensal(Request $request)
    {	
		
		//Carregar Combos
        $obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                        
                        ->orderBy('obras.nome', 'ASC')
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();
						
		$semanas = ["1","2","3","4","5"];

		$meses = [];
				
		return view('admin.cronograma_fisicos.relMensal', compact('obras', 'semanas', 'meses'));
               
    }
	
	public function mensalCarregarTabelas(Request $request){
				
		//Carregar Dados Iniciais	
		$tabColetaSemanal = [];
		$tabColetaSemanal['labels1'] = [];
		$tabColetaSemanal['labels2'] = [];
		$tabColetaSemanal['data'] = [];
		
		$tabPrevistoRealizado = [];		
		$tabPrevistoRealizado['labels'] = [];
		$tabPrevistoRealizado['data']['pdp'] = [];
		$tabPrevistoRealizado['data']['trabalho'] = [];
		$tabPrevistoRealizado['data']['tendencia-real'] = [];
		$tabPrevistoRealizado['data']['tendencia-trabalho'] = [];
		$tabPrevistoRealizado['data']['realizado'] = [];
		
		$assertividadeMensal = [];
		
		//Filtros Obra, Mês	
		if($request->obra_id != "") {            
            
			$obraId = $request->obra_id;
			
			$obraDataInicio = Obra::select([
							'obras.id',
							'obras.data_inicio'							
						])											
						->where('obras.id', $obraId)						
                        ->orderBy('obras.id', 'ASC')                        
                        ->first()
						->data_inicio;	
								
			$obraDataInicio = Carbon::parse($obraDataInicio);
			
			if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
				$obraDataFinal = Carbon::now();
			}else{
				$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
			}
			
			$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
			$mesesObra = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, Carbon::parse($obraDataInicio)->addMonths(36)); // Todos os meses da Obra
			
		}		
						
		if($request->mes_id != "") { 
			
			$mesId = $request->mes_id;
			$mesRef = $meses[$mesId];
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->startOfMonth();
			$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->endOfMonth();
						
		}
						
		//Retornar as Regras
		if($request->obra_id != "" && $request->mes_id != ""){
								
			/***** Tabela Coleta Semanal - Dados, pega da Tendencia Real ****/
			$tabColetaSemanal['data'] = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, "Tendência Real");			
						
			/***** Tabela Percentual Previsto e Acumulado ****/	
			$tabPrevistoRealizado['labels'] = array_slice($meses, $mesId, 4);    ; //Label Horizontal
			$mesesRef = $tabPrevistoRealizado['labels'];
						
			/***** Tabela Percentual Previsto x Percentual Realizado - Dados: Vindo da Curva de Andamento (PD, PT, TR, TD e TT) ****/
			$percentualDiretorMeses = $this->percentualPorMeses($obraId, $meses, "Plano Diretor"); //% de tarefas em mes e sem
			$percentualTrabalhoMeses = $this->percentualPorMeses($obraId, $meses, "Plano Trabalho"); //% de tarefas em mes e sem
			$percentualTendeciaRealMeses = $this->percentualPorMeses($obraId, $meses, "Tendência Real"); //% de tarefas em mes e sem
			$percentualTendeciaTrabalhoMeses = $this->percentualPorMeses($obraId, $meses, "Tendência Trabalho"); //% de tarefas em mes e sem
								
			$tabPrevistoRealizado['data']['pdp'] = $this->mensalTabPrevistoRealizado($percentualDiretorMeses, $mesesRef, "percentual");			
			$tabPrevistoRealizado['data']['trabalho'] = $this->mensalTabPrevistoRealizado($percentualTrabalhoMeses, $mesesRef , "percentual");			
			$tabPrevistoRealizado['data']['tendencia-real'] = $this->mensalTabPrevistoRealizado($percentualTendeciaRealMeses, $mesesRef , "percentual");			
			$tabPrevistoRealizado['data']['tendencia-trabalho'] = $this->mensalTabPrevistoRealizado($percentualTendeciaTrabalhoMeses, $mesesRef , "percentual");;																	
			$tabPrevistoRealizado['data']['realizado'] = $this->mensalTabPrevistoRealizado($percentualTendeciaRealMeses, $mesesRef , "realizado");;																				
			
			/**** Assertividade Mensal ***/
			$assertividadeMes = [];
			$assertividadeAcu = [];			
			
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['pdp'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['trabalho'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['tendencia-trabalho'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['realizado'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, ($assertividadeMes[3]-$assertividadeMes[2])*100);
			
			$assertividadeMensal['mensal'] = $assertividadeMes;
			
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['pdp'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['trabalho'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['tendencia-trabalho'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['realizado'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, ($assertividadeAcu[3]-$assertividadeAcu[2])*100);
			
			$assertividadeMensal['acumulado'] = $assertividadeAcu;
		}
		
		return view('admin.cronograma_fisicos.relMensalTabelas',compact('tabPrevistoRealizado', 'assertividadeMensal'));
		
	}
	
	public function mensalCarregarGraficos(Request $request){		
		
		$tabPrevistoRealizado = [];		
		$tabPrevistoRealizado['data']['pdp'] = [];
		$tabPrevistoRealizado['data']['trabalho'] = [];
		$tabPrevistoRealizado['data']['tendencia-real'] = [];
		$tabPrevistoRealizado['data']['tendencia-trabalho'] = [];
		$tabPrevistoRealizado['data']['realizado'] = [];
		
		$assertividadeMensal = [];
		
		//Filtros Obra, Mês	
		if($request->obra_id != "") {            
            
			$obraId = $request->obra_id;
			
			$obraDataInicio = Obra::select([
							'obras.id',
							'obras.data_inicio'							
						])											
						->where('obras.id', $obraId)						
                        ->orderBy('obras.id', 'ASC')                        
                        ->first()
						->data_inicio;	
								
			$obraDataInicio = Carbon::parse($obraDataInicio);
			
			if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
				$obraDataFinal = Carbon::now();
			}else{
				$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
			}
			
			$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
			$mesesObra = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, Carbon::parse($obraDataInicio)->addMonths(36)); // Todos os meses da Obra
			
		}		
						
		if($request->mes_id != "") { 
			
			$mesId = $request->mes_id;
			$mesRef = $meses[$mesId];
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->startOfMonth();
			$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mesRef)->endOfMonth();
						
		}
						
		//Retornar as Regras
		if($request->obra_id != "" && $request->mes_id != ""){				
						
			$mesesRef = array_slice($meses, $mesId, 4); 
						
			/***** Tabela Percentual Previsto x Percentual Realizado - Dados: Vindo da Curva de Andamento (PD, PT, TR, TD e TT) ****/
			$percentualDiretorMeses = $this->percentualPorMeses($obraId, $meses, "Plano Diretor"); //% de tarefas em mes e sem
			$percentualTrabalhoMeses = $this->percentualPorMeses($obraId, $meses, "Plano Trabalho"); //% de tarefas em mes e sem
			$percentualTendeciaRealMeses = $this->percentualPorMeses($obraId, $meses, "Tendência Real"); //% de tarefas em mes e sem
			$percentualTendeciaTrabalhoMeses = $this->percentualPorMeses($obraId, $meses, "Tendência Trabalho"); //% de tarefas em mes e sem
								
			$tabPrevistoRealizado['data']['pdp'] = $this->mensalTabPrevistoRealizado($percentualDiretorMeses, $mesesRef, "percentual");			
			$tabPrevistoRealizado['data']['trabalho'] = $this->mensalTabPrevistoRealizado($percentualTrabalhoMeses, $mesesRef , "percentual");	
			$tabPrevistoRealizado['data']['tendencia-real'] = $this->mensalTabPrevistoRealizado($percentualTendeciaRealMeses, $mesesRef , "percentual");	
			$tabPrevistoRealizado['data']['tendencia-trabalho'] = $this->mensalTabPrevistoRealizado($percentualTendeciaTrabalhoMeses, $mesesRef , "percentual");
			$tabPrevistoRealizado['data']['realizado'] = $this->mensalTabPrevistoRealizado($percentualTendeciaRealMeses, $mesesRef , "realizado");
										
			/**** Assertividade Mensal ***/
			$assertividadeMes = [];
			$assertividadeAcu = [];			
			
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['pdp'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['trabalho'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['tendencia-trabalho'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, $tabPrevistoRealizado['data']['realizado'][$mesesRef[0]]['mensal']);
			array_push($assertividadeMes, ($assertividadeMes[3]-$assertividadeMes[2])*100);
			
			$assertividadeMensal['mensal'] = $assertividadeMes;
			
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['pdp'][$mesesRef[0]]['acumulado']); //0
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['trabalho'][$mesesRef[0]]['acumulado']); //1
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['tendencia-trabalho'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, $tabPrevistoRealizado['data']['realizado'][$mesesRef[0]]['acumulado']);
			array_push($assertividadeAcu, ($assertividadeAcu[3]-$assertividadeAcu[2])*100);
			array_push($assertividadeAcu, ($assertividadeAcu[3]-$assertividadeAcu[0]));
			array_push($assertividadeAcu, ($assertividadeAcu[3]-$assertividadeAcu[1]));
			
			$assertividadeMensal['acumulado'] = $assertividadeAcu;
		}
		
		$grafPrevistoRealizado = $assertividadeMensal;
		
		return $grafPrevistoRealizado;
		
	}
	
	public function mensalTabPrevistoRealizado($percentualMeses, $mesesRef, $tipoDados){	
		
		$data = [];
		
		$planoMeses = $this->planoPorMeses($percentualMeses,$tipoDados); //% em mes e sem
		$planoAcumuladoMeses = $this->planoAcumuladoMeses($percentualMeses,$tipoDados); //% acum em mes e sem		
		
		foreach ($mesesRef as $mes) {
			$data[$mes]['mensal'] = $planoMeses[$mes]['mes'];		
			$data[$mes]['acumulado']  = $planoAcumuladoMeses[$mes]['mes-acumulado'];
		}
		
		return $data;
		
	}
}
