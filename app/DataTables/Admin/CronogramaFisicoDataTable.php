<?php

namespace App\DataTables\Admin;

use App\Models\CronogramaFisico;
use Form;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\CronogramaFisicoRepository;
use Carbon\Carbon;

class CronogramaFisicoDataTable extends DataTable
{
    protected $obra = null;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.cronograma_fisicos.datatables_actions')
            ->editColumn('obra_id',function ($obj){
                return $obj->obra_id ? $obj->obra->nome : '';
            })
			->editColumn('template_id',function ($obj){
                return $obj->template_id ? $obj->tipo->nome : '';
			})
            ->editColumn('data_inicio',function ($obj){
                return $obj->data_inicio ? with(new\Carbon\Carbon($obj->data_inicio))->format('d/m/Y') : '';
            })
            /*->filterColumn('data_inicio', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(cronograma_fisicos.data_inicio,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })*/
            ->editColumn('data_termino',function ($obj){
                return $obj->data_termino ? with(new\Carbon\Carbon($obj->data_termino))->format('d/m/Y') : '';
            })
            /*->filterColumn('data_termino', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(cronograma_fisicos.data_termino,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })*/
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {			
		
		//mostra a semana do mes anterior
		if($this->request()->get('mes')){
			$meses = ["07/2017","08/2017","09/2017"];
			$mesId = $this->request()->get('mes');
			$fromDate = Carbon::createFromFormat("d/m/Y", "01/".$meses[$mesId-1])->startOfMonth();
        }else{
			$fromDate = Carbon::today()->startOfMonth();
		}
		
		$fridays = CronogramaFisicoRepository::getFridaysBydate($fromDate);		
		$last_day= end($fridays);	
				
        $cronograma_fisicos = CronogramaFisico::query()
            ->select([
                'cronograma_fisicos.id',
                'obras.nome as obra',
				'template_planilhas.nome as tipo',
				'cronograma_fisicos.tarefa',
				DB::raw("(CONCAT('R$ ',cronograma_fisicos.custo,'')
                         ) as custo"
                ),
                'cronograma_fisicos.resumo',
				'cronograma_fisicos.torre',
				'cronograma_fisicos.pavimento',
				'cronograma_fisicos.critica',
				'cronograma_fisicos.data_inicio',
				'cronograma_fisicos.data_termino',
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
                         ) as semana1"
                ),
				DB::raw("(SELECT   
							CASE    
								WHEN (CF.data_inicio > '$fridays[1]') THEN '0%' 
								WHEN (CF.data_termino < '$fridays[1]') THEN '100%'							
							END AS semana2
							FROM cronograma_fisicos CF
							WHERE CF.id = cronograma_fisicos.id 
                         ) as semana2"
                ),
				DB::raw("(SELECT   
							CASE    
								WHEN (CF.data_inicio > '$fridays[2]') THEN '0%' 
								WHEN (CF.data_termino < '$fridays[2]') THEN '100%'									
							END AS semana3
							FROM cronograma_fisicos CF
							WHERE CF.id = cronograma_fisicos.id 
                         ) as semana3"
                ),
				DB::raw("(SELECT   
							CASE    
								WHEN (CF.data_inicio > '$fridays[3]') THEN '0%' 
								WHEN (CF.data_termino < '$fridays[3]') THEN '100%'								
							END AS semana4
							FROM cronograma_fisicos CF
							WHERE CF.id = cronograma_fisicos.id 
                         ) as semana4"
                ),
				DB::raw("(SELECT   
							CASE    
								WHEN (CF.data_inicio > '$last_day') THEN '0%' 
								WHEN (CF.data_termino < '$last_day') THEN '100%'								
							END AS ultimo_dia
							FROM cronograma_fisicos CF
							WHERE CF.id = cronograma_fisicos.id 
                         ) as ultimo_dia"
                ),
				DB::raw("(SELECT CONCAT(ROUND(CF.custo/
							(SELECT custo 
								FROM cronograma_fisicos  
								WHERE tarefa like '%Cronograma%'
								GROUP BY custo
							)*100,2),'%') custo_total
							FROM cronograma_fisicos CF
							WHERE CF.id = cronograma_fisicos.id 
                         ) as peso"
                ),
				DB::raw("(CONCAT(cronograma_fisicos.concluida,'%')
                         ) as concluida"
                ),                 
				'cronograma_fisicos.created_at'					
            ])
        ->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id');		
		
		if($this->request()->get('obra')){
            if(count($this->request()->get('obra')) && $this->request()->get('obra') != ""){
                $cronograma_fisicos->where('cronograma_fisicos.obra_id',$this->request()->get('obra'));
            }
        }   

		if($this->request()->get('template')){
            if(count($this->request()->get('template')) && $this->request()->get('template') != ""){
                $cronograma_fisicos->where('cronograma_fisicos.template_id',$this->request()->get('template'));
            }
        }

        return $this->applyScopes($cronograma_fisicos);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            // ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'responsive' => 'true',
                 'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+1)<max){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }' ,
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
                ],
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    public function porObra($id){
        $this->obra = $id;
        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
		//retorna os valores de trabalho baseado por semana
		/*- Se a data de início for maior da data da semana, é 0% pois não haverá produção;
		- se a data da semana for maior do que o término é 100%;
		- se não for nenhuma das 2 condições acima, divide os dias da semana da data de início - da data da sexta por os dias da semana do término -  da data do início*/			
		
        return [
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'], 					
			'tipo' => ['name' => 'template_planilhas.nome', 'data' => 'tipo'],
			'tarefa_mes' => ['name' => 'tarefa_mes', 'data' => 'tarefa_mes'],
			'tarefa' => ['name' => 'tarefa', 'data' => 'tarefa'],
			'custo' => ['name' => 'custo', 'data' => 'custo'],
			'resumo' => ['name' => 'resumo', 'data' => 'resumo'],
			'torre' => ['name' => 'torre', 'data' => 'torre'],
			'pavimento' => ['name' => 'pavimento', 'data' => 'pavimento'],
			'crítica' => ['critica' => 'critica', 'data' => 'critica'],
            'data_início' => ['name' => 'data_inicio', 'data' => 'data_inicio'],
            'data_término' => ['name' => 'data_termino', 'data' => 'data_termino'],
			'concluída' => ['name' => 'concluida', 'data' => 'concluida'],
			'peso' => ['name' => 'peso', 'data' => 'peso'],			
			//'Semana_1' => ['name' => 'concluida', 'data' => 'semana1'],
			//'Semana_2' => ['name' => 'concluida', 'data' => 'semana2'],
			//'Semana_3' => ['name' => 'concluida', 'data' => 'semana3'],
			//'Semana_4' => ['name' => 'concluida', 'data' => 'semana4'],
			//'Último_Dia' => ['name' => 'concluida', 'data' => 'ultimo_dia'],
            //'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'cronograma_fisicos';
    }
}
