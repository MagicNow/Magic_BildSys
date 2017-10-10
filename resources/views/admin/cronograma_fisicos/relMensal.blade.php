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
	
	
	.element-previsto-realizado-sem{
		height: 250px;
		padding: 5px;
		background-color: white;
	}
	
	.element-pdp-ptrab-realac{
		height: 250px;
		padding: 5px;
		background-color: white;
	}
	
	.element-desvio-pdp{
		height: 250px;
		padding: 5px;
		background-color: white;			
	}
	
	.element-desvio-ptrab{
		height: 250px;
		padding: 5px;
		background-color: white;
	}
	
	.element-tarefas-criticas{
		padding: 5px;
		background-color: white;
	}		
	
	.texto-mes{
		background-color: orange !important;
		color: white !important; 
		font-weight: 600;
		font-size:12px;
	}
	
	.fundo-branco{
		background-color:white;
		border:none;
	}
	
</style>

    <div class="content">
        <section class="content-header">
            <div class="modal-header">
                <div class="col-md-12">
                    <div class="col-md-9">
                        <h3 class="pull-left title"><a href="#" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Acompanhamento Mensal</h3>						
					</div>
                </div>
            </div>
        </section>
		
		<div class="box box-muted">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-md-4">
						{!! Form::label('obra_id', 'Obra:') !!}
						{!! Form::select('obra_id',[''=>'Selecione'] + $obras, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'obra_id', 'onchange'=>'showDados()']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('mes_id', 'Mês de Referência:') !!}
						{!! Form::select('mes_id', [''=>'Selecione a Mês de Ref.'] + $meses, null, ['class' => 'form-control select2','required'=>'required' , 'id'=>'mes_id' , 'onchange'=>'showDados()']) !!}
					</div>
				</div>
			</div>
		</div>		
		
		<div class="box-body" id="app"></div>
		
    </div>
@endsection
@section('scripts')
	@parent
    <script>
		
		var obra_id = null;
        var mes_id = null;
		
		// Funcao que retorna Objeto para Array
		function objectToArray(myObj){
			var array = $.map(myObj, function(value, index) {
				return [value];
			});
			
			return array;
		}				
		
		// Mostrar dados
		function showDados(){			
			
			obra_id = $('#obra_id').val();
			mes_id = $('#mes_id').val();
			
			// Se tiver setados todos os combos cria os gráficos
			if (obra_id !="" && mes_id !="") {
				carregarTabelas(obra_id, mes_id);				
			}
		}
		
		//Mostrar meses baseado na Obra escolhida
		function buscaMeses() {
			
			startLoading();	
            
			if (!obra_id) {
                $('#mes_id').html('');
                $('#mes_id').trigger('change.select2');
            }
			
            $.ajax('/admin/cronogramaFisicos/meses-por-obra', {
                data: {
                    obra_id: obra_id
                }
            })
			.done(function (retorno) {
								
				var options_meses = '<option value="" selected>-</option>';
										
				if(retorno){
					$.each(retorno, function (index, valor) {						
						options_meses += '<option value="' + index + '">'+ valor + '</option>';
					});
				}
				$('#mes_id').html(options_meses);
				$('#mes_id').trigger('change.select2');

			})
			.fail(function (retorno) {
				erros = '';
				$.each(retorno.responseJSON, function (index, value) {
					if (erros.length) {
						erros += '<br>';
					}
					erros += value;
				});
				swal("Oops", erros, "error");
			});
        
			stopLoading();
		}
		
		//Carregar Tabelas
		function carregarTabelas(a, b){
			
			//chama a view de graficos			
			startLoading();	            
			
            $.ajax('/admin/cronogramaFisicos/mensal-tabelas', {
                data: {
                    obra_id: a,
					mes_id: b
                }
            })
			.done(function (data) {
								
				$('#app').html(data);

			})
			.fail(function (retorno) {
				erros = '';
				$.each(retorno.responseJSON, function (index, value) {
					if (erros.length) {
						erros += '<br>';
					}
					erros += value;
				});
				swal("Oops", erros, "error");
			});
			
			carregarDadosGraficos(a,b);
        
			stopLoading();
		}
		
		//Carregar Dados do Gráfico
		function carregarDadosGraficos(a, b){
			
			$.ajax('/admin/cronogramaFisicos/mensal-graficos', {
                data: {
                    obra_id: a,
					mes_id: b
                }
            })
			.done(function (retorno) {
								
				carregarGraficos(retorno);				

			})
			.fail(function (retorno) {
				erros = '';
				$.each(retorno.responseJSON, function (index, value) {
					if (erros.length) {
						erros += '<br>';
					}
					erros += value;
				});
				swal("Oops", erros, "error");
			});			
			
		}
				
		//Carregar Gráficos com VUE		
		function carregarGraficos(data){						
						
			//Grafico Previsto x Realizado no mes selecionada
			GraficoPrevistoRealizadoMes = objectToArray(data['mensal']);			
			GraficoPrevistoRealizadoAcu = objectToArray(data['acumulado']);	
			
			GraficoPrevistoRealizadoMesLabels = ["PDP","Trabalho","Previsto","Real"];
			GraficoPrevistoRealizadoAcuLabels = ["PDP","Trabalho","Real"];
			
			//Desvio PDP e Trabalho
			GraficoDesvio = objectToArray(data['acumulado']);
		
			setTimeout(function(){
			
				const app = new Vue({
					el: '#app',
					data:{
						
						//Grafico BAR - Previsto x Realizado % Mensal
						datasetsPrevistoXRealizadoMes:
						[{
							label: 'PDP',						
							backgroundColor: 'Blue',
							borderColor: 'Blue',
							data: [GraficoPrevistoRealizadoMes[0]]
						},
						{
							label: 'Trabalho',											
							backgroundColor: 'Grey',
							borderColor: 'Grey',
							data: [GraficoPrevistoRealizadoMes[1]]
						},
						{
							label: 'Previsto',											
							backgroundColor: 'Yellow',
							borderColor: 'Yellow',
							data: [GraficoPrevistoRealizadoMes[2]]
						},
						{
							label: 'Real',											
							backgroundColor: 'DarkOrange',
							borderColor: 'DarkOrange',
							data: [GraficoPrevistoRealizadoMes[3]]
						}
						],				
						labelsPrevistoXRealizadoMes : GraficoPrevistoRealizadoMesLabels,				
						myoptionPrevistoXRealizadoMes: {
							onClick: function (event, legendItem) {
								
							},
							responsive:true,
							maintainAspectRatio:true,
							scales: {
								yAxes: [{
									ticks: {
										// Create scientific notation labels
										beginAtZero:true,
										fixedStepSize: 0.75
									}
								}]
							},
							legend: {
								display: false
							}
						},
						
						//Grafico BAR - Previsto x Realizado % Acumulado
						datasetsPrevistoXRealizadoAcu:
						[{
							label: 'PDP',						
							backgroundColor: 'Blue',
							borderColor: 'Blue',
							data: [GraficoPrevistoRealizadoAcu[0]]
						},
						{
							label: 'Trabalho',						
							backgroundColor: 'Grey',
							borderColor: 'Grey',
							data: [GraficoPrevistoRealizadoAcu[1]]
						},
						{
							label: 'Realizado',						
							backgroundColor: 'DarkOrange',
							borderColor: 'DarkOrange',
							data: [GraficoPrevistoRealizadoAcu[3]]
						}],				
						labelsPrevistoXRealizadoAcu : GraficoPrevistoRealizadoAcuLabels,
						mybooleanPrevistoXRealizadoAcu : false,                
						myoptionPrevistoXRealizadoAcu: {
							onClick: function (event, legendItem) {
								
							},
							responsive:true,
							maintainAspectRatio:true,
							scales: {
								yAxes: [{
									ticks: {
										// Create scientific notation labels
										beginAtZero:true,
										fixedStepSize: 0.75
									}
								}]
							},
							legend: {
								display: false
							}
						},
						
						//Grafico PIE - DESVIO PDP
						labelsDesvioPDP: ["DESVIO PDP"],
						myoptionDesvioPDP: {
							legend: {
								display: false
							}
						},
						datasetsDesvioPDP:[{
							data: [GraficoDesvio[5]],
							backgroundColor: 'DarkOrange',
							hoverBackgroundColor: 'DarkOrange'
						}],
						
						//Grafico PIE - DESVIO P. TRABALHO
						labelsDesvioTrabalho: ["DESVIO TRABALHO"],
						myoptionDesvioTrabalho: {
							legend: {
								display: false
							}
						},
						datasetsDesvioTrabalho:[{
							data: [GraficoDesvio[6]],
							backgroundColor: 'blue',
							hoverBackgroundColor: 'blue'
						}],
						
						//Fim dos Gráficos
										
					}
				});
			
			}, 1500);
		}				
		
		$(function () {
			
            // Colocar OnChange na Obra buscar por meses
            $('#obra_id').on('change', function (evt) {
                var v_obra = $(evt.target).val();
                obra_id = v_obra;
                buscaMeses();				
            });
			
		});
		       			
    </script>
@endsection