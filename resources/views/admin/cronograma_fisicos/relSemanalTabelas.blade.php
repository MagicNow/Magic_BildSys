<div class="row">	
	<div class="col-xs-12">               			
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Percentual Previsto x Percentual Realizado" type="created"></tile></div>
		</div>
		
		<div class="clearfix"></div>
		
		<div class="row">                        
			<div class="col-md-12 table-responsive margem-topo">
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
							@foreach($tabPercentualPrevReal['labels'] as $tabSemana)										
								<td class="text-center texto-semana">{{ $tabSemana}}</td>									
							@endforeach
						</tr>																															
						
						<tr>
							<td style="color:black;font-size:16px;font-weight:bold;">Plano Diretor Acumulado</td>
							@foreach($tabPercentualPrevReal['data']['planoDiretorAcumulado'] as $planoDiretorAcumulado)										
								<td class="text-center" style="color:black;font-weight:bold;">{{ $planoDiretorAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td style="color:black;font-size:16px;font-weight:bold;">Plano Trabalho Acumulado</td>
							@foreach($tabPercentualPrevReal['data']['planoTrabalhoAcumulado'] as $planoTrabalhoAcumulado)										
								<td class="text-center" style="color:black;font-weight:bold;">{{ $planoTrabalhoAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td>Previsto Mês Acumulado</td>
							@foreach($tabPercentualPrevReal['data']['planoPrevistoAcumulado'] as $planoPrevistoAcumulado)										
								<td class="text-center">{{ $planoPrevistoAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td>Realizado Mês Acumulado</td>
							@foreach($tabPercentualPrevReal['data']['planoRealizadoAcumulado'] as $planoRealizadoAcumulado)										
								<td class="text-center">{{ $planoRealizadoAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td>Previsto Semanal</td>
							@foreach($tabPercentualPrevReal['data']['previstoSemanal'] as $previstoSemanal)										
								<td class="text-center">{{ $previstoSemanal.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td>Realizado Semanal</td>
							@foreach($tabPercentualPrevReal['data']['realizadoSemanal'] as $realizadoSemanal)										
								<td class="text-center">{{ $realizadoSemanal.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td class="texto-semana">Desvio Semanal</td>
							@foreach($tabPercentualPrevReal['data']['desvioSemanal'] as $desvioSemanal)										
								<?php
									if($desvioSemanal >= 0){
										$classDesvioSemanal = "green";
									}else{
										$classDesvioSemanal = "red";
									}
								?>
								<td class="text-center {{ $classDesvioSemanal }}">{{ $desvioSemanal.'%'}}</td>																											
							@endforeach
						</tr>
						
					</tbody>
				</table>
			</div> 
		</div>
		
		<div class="row">                        
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">Previsto X Realizado Semanal</div>
					<div class="element-previsto-realizado-sem">                                   
						<chartjs-bar 
									 :labels="labelsPrevistoXRealizado" 
									 :datasets="datasetsPrevistoXRealizado"                                                                                                
									 :option="myoptionPrevistoXRealizado"
									 :height="220">
						</chartjs-bar>
					</div>
				</div>
			</div>
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">PDP x P.Trab. x Real Ac.</div>
					<div class="element-pdp-ptrab-realac">                                    
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

			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">DESVIO PDP</div>
					<div class="element-desvio-pdp">                                   
						<chartjs-pie 
							:labels="labelsDesvioPDP" 
							:datasets="datasetsDesvioPDP" 
							:scalesdisplay="false" 
							:option="myoptionDesvioPDP" 
							:height="220">
						</chartjs-pie>
					</div>
				</div>
			</div>
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">DESVIO P. TRABALHO</div>
					<div class="element-desvio-ptrab">                                    
						<chartjs-pie 
							:labels="labelsDesvioPTrabalho" 
							:datasets="datasetsDesvioPTrabalho" 
							:scalesdisplay="false" 
							:option="myoptionDesvioPTrabalho" 
							:height="220">
						</chartjs-pie>
					</div>
				</div>
			</div>				
			
		</div>			
	
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Tarefas Críticas" type="created"></tile></div>
		</div>
		
		<div class="clearfix"></div>
		
		<div class="row">
		
			<div class="col-md-7 table-responsive margem-topo">
				<table class="table table-bordered table-striped">
					<thead class="element-head">
						<tr>
							@foreach($tabTarefasCriticas['labels'] as $tabTarefasCriticasTitulo)										
								<td class="text-center">{{ $tabTarefasCriticasTitulo }}</td>									
							@endforeach										
						</tr>
					</thead>
					<tbody>																																																				
						
						@foreach($tabTarefasCriticas['data'] as $tabTarefasCriticasDado)
						
						<?php
							$tabTarefasPrevisto = $tabTarefasCriticasDado['previsto'];	
							$tabTarefasRealizado = $tabTarefasCriticasDado['realizado'];
							$tabTarefasDesvio = $tabTarefasRealizado - $tabTarefasPrevisto;
							if($tabTarefasDesvio >= 0){
								$colorTarefasDesvio = "green";
							}else{
								$colorTarefasDesvio = "red";
							}
						?>
						<tr>
							<td class="text-center">{{ $tabTarefasCriticasDado['local'] }}</td>
							<td class="text-center">{{ $tabTarefasCriticasDado['tarefa'] }}</td>
							<td class="text-center">{{ $tabTarefasPrevisto.'%' }}</td>
							<td class="text-center">{{ $tabTarefasRealizado.'%' }}</td>										
							<td class="text-center {{ $colorTarefasDesvio}}">{{ $tabTarefasDesvio.'%' }}</td>	
						</tr>
						@endforeach
						
					</tbody>
				</table>
			</div> 
			
			<div class="col-md-5">							
				<div class="element-grafico margem-topo">
					<div class="element-head">Tarefas Críticas</div>
					<div class="element-tarefas-criticas">                                    
						<chartjs-bar :labels="labelsTarefasCriticas" 
									 :datasets="datasetsTarefasCriticas"                                                                                                
									 :option="myoptionTarefasCriticas"
									 :height="150">
						</chartjs-bar>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Coleta Semanal" type="created"></tile></div>
		</div>
		
		<div id="coleta-semanal" class="row">
			<div class="col-md-12 table-responsive margem-topo">
				<table class="table table-bordered table-striped">
					<thead >
						<tr>
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							<th class="text-center fundo-branco"></th>	
							
							@foreach($tabColetaSemanal['labels1'] as $tabColetaSemanalSem)										
								<th class="text-center texto-semana" colspan="2">{{ $tabColetaSemanalSem}}</th>									
							@endforeach										
						</tr>
						<tr>
							@foreach($tabColetaSemanal['labels2'] as $tabColetaSemanalTitle)										
								<th class="text-center">{{ $tabColetaSemanalTitle}}</th>									
							@endforeach										
						</tr>
					</thead>
					<tbody>
						@foreach($tabColetaSemanal['data'] as $tabColetaData)										
						<tr>										
							<td class="text-center">{{ $tabColetaData['torre']}}</td>
							<td class="text-center">{{ $tabColetaData['pavimento']}}</td>
							<td class="text-center">{{ $tabColetaData['tarefa']}}</td>
							<td class="text-center">{{ with(new\Carbon\Carbon($tabColetaData['data_inicio']))->format('d/m/Y')}}</td>
							<td class="text-center">{{ with(new\Carbon\Carbon($tabColetaData['data_termino']))->format('d/m/Y')}}</td>
							<td class="text-center">{{ $tabColetaData['critica']}}</td>
							<td class="text-center">{{ $tabColetaData['peso'].'%'}}</td>
							<td class="text-center">{{ $tabColetaData['concluida'].'%'}}</td>
							
							<?php foreach ($tabColetaSemanal['labels1'] as $coletaSemana) {	?>
									<td class="text-center"><?php echo $tabColetaData['percentual-'.$coletaSemana]."%"; ?></td>
									<td class="text-center"><?php echo $tabColetaData['realizado-'.$coletaSemana]."%"; ?></td>
							<?php } ?>
							<td class="text-center"></td>
							<td class="text-center"></td>
							<td class="text-center"></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>								
		
	</div>
</div>
