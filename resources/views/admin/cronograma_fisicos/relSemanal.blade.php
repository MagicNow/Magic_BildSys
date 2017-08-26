@extends('layouts.front')

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
            height: 300px;
            padding: 15px;
            background-color: white;
        }
		
		.element-body2{
            height: 120px;
            padding: 5px;
            background-color: white;
        }
    </style>

    <div class="container">
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
							'obra_id',$obras,null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Ano</h4>
						{!!
						  Form::select(
							'ano_id',["2017","2018","2019","2020"],null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Mês</h4>
						{!!
						  Form::select(
							'mes_id',["Janeiro","Fevereiro"],null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Semana</h4>
						{!!
						  Form::select(
							'semana_id',["Semana 1","Semana 2","Semana 3","Semana 4"],null,['class' => 'form-control select2 js-filter']
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
						<div class="col-md-12"><tile title-color="head-grey" title="Percentual Previsto x Precentual Realizado" type="created"></tile></div>
					</div>
					</br>
					<div class="row">                        
						<div class="col-md-4">							
                            Tabela com Valores
                        </div> 						
						<div class="col-md-4">							
                            <div class="element-grafico">
                                <div class="element-head">Previsto X Realizado Semanal</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar :datalabel="mylabelPrevistoXRealizado"
                                                 :labels="labelsPrevistoXRealizado" 
												 :datasets="datasetsPrevistoXRealizado"
                                                 :beginzero="mybooleanPrevistoXRealizado"                                                 
                                                 :option="myoptionPrevistoXRealizado"
                                                 :height="250">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-4">							
                            <div class="element-grafico" id="chartPDPxPTrabalhoxRealAc">
                                <div class="element-head">PDP x P.Trabalho x Real Acumulado</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-bar :datalabel="mylabelPDPxPTrabalhoxRealAc"
                                                 :labels="labelsPDPxPTrabalhoxRealAc" 
												 :datasets="datasetsPDPxPTrabalhoxRealAc"
                                                 :beginzero="mybooleanPDPxPTrabalhoxRealAc"                                                 
                                                 :option="myoptionPDPxPTrabalhoxRealAc"
                                                 :height="250">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>												
                    </div>
                    </br>					              				
					<div class="row">						
						<div class="col-md-3">							
                            <div class="element-grafico" id="chartPDPxPTrabalhoxRealAc">
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
						
						<div class="col-md-3">							
                            <div class="element-grafico" id="chartPDPxPTrabalhoxRealAc">
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
					</br>
					<div class="row">
						<div class="col-md-12"><tile title-color="head-grey" title="Tarefas Críticas" type="created"></tile></div>
					</div>
					</br>
					<div class="row">
                        <div class="col-md-4">							
                            Tabela com Valores
                        </div> 
						
						<div class="col-md-4">							
                            <div class="element-grafico">
                                <div class="element-head">Tarefas Críticas</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar :datalabel="mylabelTarefasCriticas"
                                                 :labels="labelsTarefasCriticas" 
												 :datasets="datasetsTarefasCriticas"
                                                 :beginzero="mybooleanTarefasCriticas"                                                 
                                                 :option="myoptionTarefasCriticas"
                                                 :height="250">
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
    <script>
	
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
                                fixedStepSize: 0.20
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