@extends('layouts.app')

@section('content')
    <style>
		
		.element-text{
            width: 100%;
            border: solid 1px #dddddd;
        }
		
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
		
        .element-mensal{
            height: 220px;
            padding: 15px;
            background-color: white;
        }
		
		.element-acumulada{
            height: 220px;
            padding: 15px;
            background-color: white;
        }
		
		.element-desvio-pdp{
            height: 180px;
            padding: 5px;
            background-color: white;
        }
		
		.element-desvio-trabalho{
            height: 180px;
            padding: 5px;
            background-color: white;
        }
		
		.element-desvio-prazo{
            height: 300px;
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
							'mes_id',["Julho","Agosto"],null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Data</h4>
						@include('partials.filter-date')
					</div>
				</div>
			</div>
		</div>
		
        <div class="box-body" id="app">
            <div class="row">
                <div class="col-xs-12">                    
                    
					<div class="row">
						<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Previsto x Realizado" type="created"></tile></div>
					</div>
					</br>
					<div class="row">                        
						
						<div class="col-md-6 table-responsive margem-topo">
							<div class="element-grafico">
                                <div class="element-head">ASSERTIVIDADE MENSAL</div>
							</div>
							<table class="table table-bordered table-striped">
								<thead></thead>
								<tbody>																		
									<tr>
										<td class="text-center">PDP</td>
										<td class="text-center">TRABALHO</td>										
										<td class="text-center">PREVISTO</td>
										<td class="text-center">REAL</td>
										<td class="text-center">ASSERTIVIDADE</td>
									</tr>
									<tr>
										<td class="text-center">{{ $assertividadeMensal[0]}}</td>
										<td class="text-center">{{ $assertividadeMensal[1]}}</td>
										<td class="text-center">{{ $assertividadeMensal[2]}}</td>										
										<td class="text-center">{{ $assertividadeMensal[3]}}</td>
										<td class="text-center">{{ $assertividadeMensal[4]}}</td>
									</tr>									
								</tbody>
							</table>
                        </div>
						
						<div class="col-md-6 table-responsive margem-topo">
							<table class="table table-bordered table-striped">
								<thead >
									<tr>										
										<th class="text-center">jun-17</th>
										<th class="text-center"></th>
										<th class="text-center">jul-17</th>
										<th class="text-center"></th>
										<th class="text-center">ago-17</th>
										<th class="text-center"></th>
										<th class="text-center">set-17</th>
										<th class="text-center"></th>
									</tr>
								</thead>
								<tbody>
																		
									<tr>
										<td class="text-center">% Mens.</td>
										<td class="text-center">% Acum.</td>										
										<td class="text-center">% Mens.</td>
										<td class="text-center">% Acum.</td>
										<td class="text-center">% Mens.</td>
										<td class="text-center">% Acum.</td>
										<td class="text-center">% Mens.</td>
										<td class="text-center">% Acum.</td>
									</tr>																															
																		
									<tr>
										<td class="text-center">111</td>
										<td class="text-center">111</td>										
										<td class="text-center">222</td>
										<td class="text-center">222</td>
										<td class="text-center">333</td>										
										<td class="text-center">333</td>
										<td class="text-center">444</td>
										<td class="text-center">444</td>																				
									</tr>									
								</tbody>
							</table>
                        </div> 
						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">%Mensal</div>
                                <div class="element-mensal">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-bar 
                                                 :labels="labelsPercMensal" 
												 :datasets="datasetsPercMensal"
                                                                                                  
                                                 :option="myoptionPercMensal"
                                                 :height="200">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">%Acumulada</div>
                                <div class="element-acumulada">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$pDPxPTrabalhoxRealAc]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1']);
									@endphp
									<chartjs-bar 
                                                 :labels="labelsPercAcumulada" 
												 :datasets="datasetsPercAcumulada"
                                                                                                 
                                                 :option="myoptionPercAcumulada"
                                                 :height="200">
									</chartjs-bar>
                                </div>
                            </div>
                        </div>	
						
						<div class="col-md-3 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">DESVIO PDP</div>
                                <div class="element-desvio-pdp">
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
                                <div class="element-head">DESVIO TRABALHO</div>
                                <div class="element-desvio-trabalho">
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
					
					<div class="row">
						<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Desvio De Prazo" type="created"></tile></div>
					</div>
					
					<div class="row">           						
						<div class="col-md-7 margem-topo">							
                            <div class="element-grafico">
                                <div class="element-head">Desvio de Prazo</div>
                                <div class="element-desvio-prazo">
                                    @php
										//$json_dataPrevistoXRealizado = json_encode([$previstoXRealizado]);                        
										//$json_labelsPrevistoXRealizado = json_encode(['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Mês']);
									@endphp
									<chartjs-line 
											:labels="labelsDesvioPrazo" 
											:datasets="datasetsDesvioPrazo" 
											:scalesdisplay="true" 
											:option="myoptionDesvioPrazo" 
											:height="120">
									</chartjs-line>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-5 margem-topo">							
                            <div class="element-grafico">
								<div class="element-head">Observações</div>
								
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
				//Grafico Line - DesvioPrazo
                datasetsDesvioPrazo:
				[{
					label: 'Data do Cliente',						
					backgroundColor: 'blue',
					hoverBackgroundColor: 'blue',
					data: [111]
				},
				{
					label: 'Plano Diretor',						
					backgroundColor: 'black',
					hoverBackgroundColor: 'black',
					data: ["11111", "30/10/2010","30/10/2010"]
				},
				{
					label: 'Plano Trabalho',						
					backgroundColor: 'orange',
					hoverBackgroundColor: 'orange',
					data: ["11111", "30/10/2010","30/10/2010"]
				},
				{
					label: 'Tendência Diretor',						
					backgroundColor: 'purple',
					hoverBackgroundColor: 'purple',
					data: ["11111", "30/10/2010","30/10/2010"]
				},
				{
					label: 'Tendência Trabalho',						
					backgroundColor: 'yellow',
					hoverBackgroundColor: 'yellow',
					data: ["11111", "30/10/2010","30/10/2010"]
				},
				{
					label: 'Tendência Real',						
					backgroundColor: 'green',
					hoverBackgroundColor: 'green',
					data: ["11111", "30/10/2010","30/10/2010"]
				},				
				{
					label: 'Habite-se',						
					backgroundColor: 'red',
					hoverBackgroundColor: 'red',
					data: ["11111", "30/10/2010","30/10/2010"]
				}],				
                labelsDesvioPrazo : ["ABR-17", "MAI-17", "JUN-17"],				
                myoptionDesvioPrazo: {                    
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
						yAxes: [{
							type: 'time',
							time: {
								displayFormats: {
									quarter: 'III'
								}
							}
						}]
					},
                    legend: {
                        display: true
                    }
                }
                
								
            }
        });
		        			
    </script>
@endsection