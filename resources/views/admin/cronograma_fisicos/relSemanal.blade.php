@extends('layouts.app')

@section('content')
    <style>
        .element-grafico{
            width: 100%;
            border: solid 1px #dddddd;
        }
        .element-head {
            text-align: center;
            color: #f5f5f5;
            padding: 10px 0px 10px 0px;
            background-color: #474747;
            font-family: Raleway;
            font-weight: bold;
        }
        
		.element-body1{
            height: 200px;
            padding: 5px;
            background-color: white;
        }
		
		.element-body-tarefaCriticas{
            height: 250px;
            padding: 5px;
            background-color: white;
        }
		
		.element-body2{
            height: 120px;
            padding: 5px;
            background-color: white;
        }
		
		.table> thead> tr> th, .table> thead> tr> td{
			background-color:orange;
			color:white;
		}
		
    </style>

    <div class="content">
        <section class="content-header">
            <div class="modal-header">
                <div class="col-md-12">
                    <div class="col-md-9">
                        <h3 class="pull-left title"><a href="#" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Acompanhamento Semanal</h3>						
					</div>
                </div>
            </div>
        </section>
        {{--@include('layouts.filters')--}}
		
		<div class="box box-muted">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-3">
						<h4>Obra</h4>						
						{!! 
							Form::select(
								'obra_id', $obras, null, ['class' => 'form-control select2', 'onchange' => 'selecionaObra(this.value)']) 
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Data</h4>
						@include('partials.filter-date')
					</div>
					<div class="col-sm-2">
						<h4>Semana</h4>
						{!!
						  Form::select(
							'semana_id',$fridays,null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
				</div>
			</div>
		</div>
		
        <div class="box-body" id="app">
            <div class="row">
                <div class="col-xs-12">                    
                    
					<div class="row">
						<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Percentual Previsto x Percentual Realizado" type="created"></tile></div>
					</div>
					
					<div class="clearfix"></div>
					
					<div class="row">                        
						<div class="col-md-7 table-responsive margem-topo">
							<table class="table table-bordered table-striped">
								<thead >
									<tr>
										<th class="text-center"></th>
										<th class="text-center">Semana 1</th>
										<th class="text-center">Semana 2</th>
										<th class="text-center">Semana 3</th>
										<th class="text-center">Semana 4</th>
										<th class="text-center">Semana 5</th>										
										<th class="text-center">Mês</th>										
									</tr>
								</thead>
								<tbody>
																		
									<tr>
										<td class="text-center"></td>	
										@foreach($tabelaPercPrevistoRealizadosSemanas as $tabelaSemana)										
											<td class="text-center">{{ Carbon\Carbon::parse($tabelaSemana)->format('d/m/Y') }}</td>									
										@endforeach
									</tr>																															
									
									@foreach($tabelaPercPrevistoRealizados as $tabelaPercPrevistoRealizado)	
									<tr>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->tipo}}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>
										<td class="text-center">{{ $tabelaPercPrevistoRealizado->concluida }}</td>																			
									</tr>
									@endforeach
								</tbody>
							</table>
                        </div> 
						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">Previsto X Realizado Semanal</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar 
                                                 :labels="labelsPrevistoXRealizado" 
												 :datasets="datasetsPrevistoXRealizado"                                                                                                
                                                 :option="myoptionPrevistoXRealizado"
                                                 :height="220">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-2 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">PDP x P.Trab. x Real Ac.</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-bar 
                                                 :labels="labelsPDPxPTrabalhoxRealAc" 
												 :datasets="datasetsPDPxPTrabalhoxRealAc"
                                                 :beginzero="mybooleanPDPxPTrabalhoxRealAc"                                                 
                                                 :option="myoptionPDPxPTrabalhoxRealAc"
                                                 :height="220">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>												
                    </div>
                    
					<div class="clearfix"></div>
					
					<div class="row">						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">DESVIO PDP</div>
                                <div class="element-body2">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-pie 
										:labels="labelsDesvioPDP" 
										:datasets="datasetsDesvioPDP" 
										:scalesdisplay="false" 
										:option="myoptionDesvioPDP" 
										:height="120">
									</chartjs-pie>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">DESVIO P. TRABALHO</div>
                                <div class="element-body2">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-pie 
										:labels="labelsDesvioPTrabalho" 
										:datasets="datasetsDesvioPTrabalho" 
										:scalesdisplay="false" 
										:option="myoptionDesvioPTrabalho" 
										:height="120">
									</chartjs-pie>
                                </div>
                            </div>
                        </div>						
                    </div>
					
					<div class="clearfix"></div>
					
					<div class="row">
						<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Tarefas Críticas" type="created"></tile></div>
					</div>
					
					<div class="clearfix"></div>
					
					<div class="row">
					
                        <div class="col-md-7 table-responsive margem-topo">
							<table class="table table-bordered table-striped">
								<thead class="element-tabela-head">
									<tr>
										@foreach($tabelaTarefasCriticasTitulos as $tabelaTarefasCriticasTitulo)										
											<td class="text-center">{{ $tabelaTarefasCriticasTitulo }}</td>									
										@endforeach										
									</tr>
								</thead>
								<tbody>																																																				
									
									@foreach($tabelaTarefasCriticasDados as $tabelaTarefasCriticasDado)										
									<tr>
										<td class="text-center">{{ $tabelaTarefasCriticasDado->local }}</td>
										<td class="text-center">{{ $tabelaTarefasCriticasDado->tarefa }}</td>
										<td class="text-center">{{ $tabelaTarefasCriticasDado->previsto_acumulado}}</td>
										<td class="text-center">{{ $tabelaTarefasCriticasDado->realizado_acumulado}}</td>
										<td class="text-center">{{ $tabelaTarefasCriticasDado->previsto_acumulado - $tabelaTarefasCriticasDado->realizado_acumulado}}</td>										
									</tr>
									@endforeach
									
								</tbody>
							</table>
                        </div> 
						
						<div class="col-md-4">							
                            <div class="element-grafico margem-topo">
                                <div class="element-head">Tarefas Críticas</div>
                                <div class="element-body-tarefaCriticas">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar :labels="labelsTarefasCriticas" 
												 :datasets="datasetsTarefasCriticas"                                                                                                
                                                 :option="myoptionTarefasCriticas"
                                                 :height="200">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
					</div>
					
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
	@parent
    <script>		
		
		function selecionaObra(value) {
			
			startLoading();			
			
			// Carrega a MC que já foi medida para a torre.
			history.pushState("", document.title, location.pathname+'?obra_id='+value);
			location.reload();
			

			stopLoading();
		}
	
		const app = new Vue({
            el: '#app',
            data:{
				
				//Grafico BAR - Previsto x Realizado
                datasetsPrevistoXRealizado:
				[{
					label: 'Previsto',						
					backgroundColor: 'Maroon',
					borderColor: 'Maroon',
					data: [-1.58,1.75,1.75,1.75,1.75,2.55]
				},
				{
					label: 'Realizado',											
					backgroundColor: 'DarkOrange',
					borderColor: 'DarkOrange',
					data: [1.58,1.75,1.75,1.75,1.75,2.55]
				}],				
                labelsPrevistoXRealizado : ["Semana 1", "Semana 2", "Semana 3", "Semana 4", "Semana 5", "Mês"],
				//mylabelPrevistoXRealizado : '%',
				//mybackgroundcolor : ['rgba(255,0,0,1)','rgba(249,141,0,1)','rgba(126, 211, 33,1)'],
                //mybordercolor : ['rgba(255,0,0,1)','rgba(249,141,0,1)','rgba(126, 211, 33,1)'],
                //mybooleanPrevistoXRealizado : false,
                myoptionPrevistoXRealizado: {
                    onClick: function (event, legendItem) {
						
					},
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                beginAtZero:true,
                                fixedStepSize: 0.50
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
				
				,				
				//Grafico BAR - PDP x P.Trabalho x Real Acumulado
                datasetsPDPxPTrabalhoxRealAc:
				[{
					label: 'Plano Diretor',						
					backgroundColor: 'grey',
					borderColor: 'grey',
					data: [1.58]
				},
				{
					label: 'Plano Trabalho',						
					backgroundColor: 'blue',
					borderColor: 'blue',
					data: [1.58]
				},
				{
					label: 'Realizado',						
					backgroundColor: 'DarkOrange',
					borderColor: 'DarkOrange',
					data: [1.17]
				}],				
                labelsPDPxPTrabalhoxRealAc : ["Semana 1"],
				//mylabelPrevistoXRealizado : '%',
                mybooleanPDPxPTrabalhoxRealAc : false,                
                myoptionPDPxPTrabalhoxRealAc: {
                    onClick: function (event, legendItem) {
						
					},
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                beginAtZero:true,
                                fixedStepSize: 0.5
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
				
				,				
				//Grafico PIE - DESVIO PDP
				labelsDesvioPDP: ["DESVIO PDP"],
                myoptionDesvioPDP: {
                    /*onClick: function (event, legendItem) {
                        window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;
                    }*/
                },
                datasetsDesvioPDP:[{
                    data: [100],
                    backgroundColor: 'DarkOrange',
                    hoverBackgroundColor: 'DarkOrange'
                }]
				
				,				
				//Grafico PIE - DESVIO P. TRABALHO
				labelsDesvioPTrabalho: ["DESVIO P. TRABALHO"],
                myoptionDesvioPTrabalho: {
                    /*onClick: function (event, legendItem) {
                        window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;
                    }*/
                },
                datasetsDesvioPTrabalho:[{
                    data: [100],
                    backgroundColor: 'blue',
                    hoverBackgroundColor: 'blue'
                }]
				
				,				
				//Grafico BAR - Tarefas Críticas
                datasetsTarefasCriticas:
				[{
					label: 'Previsto Acumulado',						
					backgroundColor: 'maroon',
					borderColor: 'maroon',
					data: [90.91, 88.24, 0.00, 0.00]
				},
				{
					label: 'Realizado Acumulado',
					backgroundColor: 'DarkOrange',
					borderColor: 'DarkOrange',
					data: [95.00, 0.00, 0.00, 0.00]
				}],				
                labelsTarefasCriticas : ["Gabarito", "Rebaixamento Do Lençol", "Tub. 98 tub. - 4 por dia", "Blocos de Fund."],
				//mylabelPrevistoXRealizado : '%',
                mybooleanTarefasCriticas : false,                
                myoptionTarefasCriticas: {
                    onClick: function (event, legendItem) {
						
					},
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                beginAtZero:true,
                                fixedStepSize: 25
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
								
            }
        });
		        			
    </script>
@endsection