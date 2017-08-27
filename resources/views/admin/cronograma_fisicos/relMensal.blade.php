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
                        <h3 class="pull-left title"><a href="#" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
						Acompanhamento Mensal</h3>						
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
					<div class="col-sm-2">
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
				</div>
			</div>
		</div>
		
        <div class="box-body" id="app">
            <div class="row">
                <div class="col-xs-12">                    
                    
					<div class="row">
						<div class="col-md-12"><tile title-color="head-grey" title="Previsto x Realizado" type="created"></tile></div>
					</div>
					</br>
					<div class="row">                        
						<div class="col-md-4">							
                            <p>Tabela com Valores 1</p> 
							<p>Tabela com Valores 2</p>
                        </div> 						
						<div class="col-md-4">							
                            <div class="element-grafico">
                                <div class="element-head">%Mensal</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar :datalabel="mylabelPercMensal"
                                                 :labels="labelsPercMensal" 
												 :datasets="datasetsPercMensal"
                                                 :beginzero="mybooleanPercMensal"                                                 
                                                 :option="myoptionPercMensal"
                                                 :height="250">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-4">							
                            <div class="element-grafico">
                                <div class="element-head">%Acumulada</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-bar :datalabel="mylabelPercAcumulada"
                                                 :labels="labelsPercAcumulada" 
												 :datasets="datasetsPercAcumulada"
                                                 :beginzero="mybooleanPercAcumulada"                                                 
                                                 :option="myoptionPercAcumulada"
                                                 :height="250">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>												
                    </div>
                    </br>					              				
					<div class="row">						
						<div class="col-md-3">							
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
						
						<div class="col-md-3">							
                            <div class="element-grafico">
                                <div class="element-head">DESVIO TRABALHO</div>
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
						<div class="col-md-12"><tile title-color="head-grey" title="Desvio De Prazo" type="created"></tile></div>
					</div>
					</br>
					<div class="row">                        
						
						<div class="col-md-4">							
                            <div class="element-grafico">
                                <div class="element-head">Desvio de Prazo</div>
                                <div class="element-body1">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-line :datalabel="mylabelDesvioPrazo"
                                                 :labels="labelsDesvioPrazo" 
												 :datasets="datasetsDesvioPrazo"
                                                 :beginzero="mybooleanDesvioPrazo"                                                 
                                                 :option="myoptionDesvioPrazo"
                                                 :height="250">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-4">							
                            <p>Tabela com Valores 1</p> 
							<p>Tabela com Valores 2</p>
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
				
				//Grafico BAR - % Mensal
                datasetsPercMensal:
				[{
					label: 'Mensal',						
					backgroundColor: ['blue','grey', 'DarkOrange', 'red'],
					borderColor: ['blue','grey', 'DarkOrange', 'red'],
					data: [0.68, 0.68, 0.97, 0.71]
				}],				
                labelsPercMensal : ["PDP", "Trabalho", "Previsto", "Real"],				
                myoptionPercMensal: {
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
				//Grafico BAR - % Acumulada
                datasetsPercAcumulada:
				[{
					label: 'Acumulada',						
					backgroundColor: ['blue','grey', 'red'],
					borderColor: ['blue','grey', 'red'],
					data: [1.68, 1.68, 1.97]
				}],				
                labelsPercAcumulada : ["PDP", "Trabalho", "Real"],				
                myoptionPercAcumulada: {
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
				//Grafico PIE - DESVIO PDP
				labelsDesvioPDP: ["DESVIO PDP"],
                myoptionDesvioPDP: {
                    /*onClick: function (event, legendItem) {
                        window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;
                    }*/
                },
                datasetsDesvioPDP:[{
                    data: [100],
                    backgroundColor: 'blue',
                    hoverBackgroundColor: 'blue'
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
                    backgroundColor: 'maroon',
                    hoverBackgroundColor: 'maroon'
                }]
				
				,				
				//Grafico Line - Desvio Prazo
                datasetsDesvioPrazo:
				[{
					label: 'Previsto Acumulado',						
					backgroundColor: 'maroon',
					borderColor: 'maroon',
					data: ['30/10/2010']
				},
				{
					label: 'Realizado Acumulado',
					backgroundColor: 'DarkOrange',
					borderColor: 'DarkOrange',
					data: [95.00, 0.00, 0.00, 0.00]
				}],				
                labelsDesvioPrazo : ["abr/2017", "mai/2017", "jun/2017"],
				//mylabelPrevistoXRealizado : '%',
                mybooleanDesvioPrazo : false,                
                myoptionDesvioPrazo: {
                    onClick: function (event, legendItem) {
						
					},
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
						yAxes: [{
							type: "time",
							display: true,
							time: {
								format: 'MM/DD/YYYY',
								round: 'day'
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