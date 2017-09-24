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
	
	################################ Relatórios ###################################
	
	//Acompanhamento Semanal
	public function relSemanal(Request $request, CronogramaFisicoDataTable $cronogramaFisicoDataTable)
    {	
		
		//Carregar Dados Iniciais
		$obraId = 0;
		$mesId = 0;
		$semanaId = 0;
		
		$meses = ["2017-09"];
		$semanas = ["1","2","3","4","5"];
		
		$inicioMes = "2017-07-01";
		$fimMes = "2017-07-31";
		
		//Carregar Combos
        $obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                        
                        ->orderBy('obras.nome', 'ASC')
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();
		
		//Filtros Obra, Mês e Semana de Referências
		
		if($request->obra_id) {            
            
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
			
		}		
		
		if($request->mes_id) {
            
			$mesId = $request->mes_id;	
			//$meses[$mesId];
			
			/*Pegar o intervalo de datas do mes anterior ao mes de referencia
			$inicioMes = "2017-07-01";
			$fimMes = "2017-07-31";		*/
			
		}
			
		if($request->semana_id) {
			$semanaId = $request->semana_id;
		}		
		
		// Data de Inicio
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$ultimaSexta= end($sextasArray);
		
		//Data da Sexta feira da Semana de Referência
		$semanaSexta = $sextasArray[$semanaId];
		
		//Tabela Coleta Semanal - Dados
		$tabColetaSemanal = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, $sextasArray);
		
		//Tabela Percentual Previsto e Acumulado		
		$tabPercPrevistoRealizados['labels'] = $sextasArray; //Label
		$tabPercPrevistoRealizados['data'] = $this->tabPercPrevistoRealizados($obraId, $semanaSexta); //Dados
			
		
		//Tabela Tarefas Criticas
		$tabTarefasCriticas = [];
		$tabTarefasCriticas['labels'] = ["LOCAL","Tarefas Críticas","Previsto Ac.","Realizado Ac.","Desvio"]; //Label 		
		$tabTarefasCriticas['data'] = $this->tabTarefasCriticas($tabColetaSemanal, $semanaSexta); //Dados
								
		//Gráfico Tarefas Críticas		
		$grafTarefasCriticas = [];
		$grafTarefasCriticasLabels = [];
		
		foreach ($tabTarefasCriticas['data'] as $tmp1) {			
			array_push($grafTarefasCriticasLabels, ltrim($tmp1['tarefa']));							
		}
		$grafTarefasCriticas['labels'] = $grafTarefasCriticasLabels;
		$grafTarefasCriticas['data'] = $tabTarefasCriticas['data'];
		
		print_r($grafTarefasCriticas);die;
		print_r();
				
		//End Gráfico Tarefas Criticas
		
        return 
			$cronogramaFisicoDataTable->render('admin.cronograma_fisicos.relSemanal', 
				compact(
					'obraId', 'mesId', 'semanaId', 'obras', 'meses', 'semanas',
					'tabPercPrevistoRealizados', 
					'tabTarefasCriticas', 'grafTarefasCriticas'
				)
			);
    }	
	
	// Tabela Coleta Semanal - Dados
	public function tabColetaSemanal($obraId, $inicioMes, $fimMes, $sextasArray){	
		
		//DB::enableQueryLog();
		
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
			DB::raw("(SELECT (case when count(distinct CF.id) = 1 then 'Sim' else 'Não' end) as tarefa_mes
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id						
						AND (CF.data_termino >= '$inicioMes')						
						AND (CF.data_inicio <= '$fimMes')
					 ) as tarefa_mes"
			),               
			'cronograma_fisicos.created_at'					
		])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')
		->where('cronograma_fisicos.obra_id', $obraId)
		->where('cronograma_fisicos.resumo','Não')				
		->where('template_planilhas.nome','Plano Diretor')		
		->orderBy('cronograma_fisicos.data_inicio', 'desc')		
		->get()
		->toArray();
		
		//dd(DB::getQueryLog());	

		//Filtrar por Tarefa do Mês				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {				
			
			if($tarefa['tarefa_mes']!='Sim'){
				
				unset($tabColetaSemanal[$keyT]);
			
			}else{
				
				foreach($sextasArray as $keyS => $sexta){								
					
					//Calcular % da Semana
					$inicioTarefa = Carbon::parse($tarefa['data_inicio']);
					$fimTarefa = Carbon::parse($tarefa['data_termino']);
					
					$inicioSemana = Carbon::parse($sexta)->subDays(6);
					$fimSemana = Carbon::parse($sexta);								
					
					$valorPrevisto = 0;
		
					if($inicioTarefa > $fimSemana){
						$valorPrevisto = 0;
					}elseif($fimSemana > $fimTarefa){
						$valorPrevisto = 100;
					}else{
						$valorPrevisto = CronogramaFisicoRepository::getDiasUteis($inicioSemana, $fimSemana)/ CronogramaFisicoRepository::getDiasUteis($inicioTarefa, $fimTarefa);
					}	
					$keyS=$keyS+1;
					$tabColetaSemanal[$keyT]["previsto-".$sexta] =  $valorPrevisto;
					$tabColetaSemanal[$keyT]["realizado-".$sexta] =  0; //Mediçao Fisicas
					//Mes
					//Farol
					
				}				
			}	
		}		
		
		return $tabColetaSemanal;
	}
	
	// Tabela Previsto e Realizados - Dados
	public function tabPercPrevistoRealizados($obraId, $semanaSexta){
		
		$tabPercPrevistoRealizados = CronogramaFisico::select([
			'cronograma_fisicos.id',                
			'template_planilhas.nome as tipo',				                												
				DB::raw("(CONCAT(cronograma_fisicos.concluida,'%')
					) as concluida"
			),                 
			'cronograma_fisicos.data_inicio'		
		])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')		
		->whereDate('cronograma_fisicos.data_inicio','>=',Carbon::createFromFormat('Y-m-d', $semanaSexta)->toDateString())
		->where('cronograma_fisicos.obra_id', $obraId)
		->orderBy('id', 'desc')
		->take(7)
		->get();
		
		return $tabPercPrevistoRealizados;
	}
	
	// Tabela Tarefas Criticas - Dados
	public function tabTarefasCriticas($tabColetaSemanal, $semanaSexta){
		
		$tabTarefasCriticas = [];
				
		//Filtrar por Critica				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {
			
			$tmp1="previsto-".$semanaSexta;
			$tmp2="realizado-".$semanaSexta;
					
			if($tarefa['critica']=='Sim'){					
				
				$tabTarefasCriticas[$keyT]['local'] = $tarefa['torre'];
				$tabTarefasCriticas[$keyT]['tarefa'] = ltrim($tarefa['tarefa']);
				$tabTarefasCriticas[$keyT]['previsto'] = $tarefa[$tmp1];
				$tabTarefasCriticas[$keyT]['realizado'] = $tarefa[$tmp2];
			
			}
			
				
		}		
		
		return $tabTarefasCriticas;
	}
	
	public function relMensal(Request $request)
    {	
		
		$obras = Obra::pluck('nome','id')->toArray(); 	
		
		$assertividadeMensal = [0.68, 0.68, 0.97, 0.71, -26.80];
               
        return view('admin.cronograma_fisicos.relMensal', compact('obras', 'assertividadeMensal'));
    }
	
	

}
