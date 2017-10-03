<div class="row">	
	<div class="col-xs-12">               			
		
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Previsto x Realizado" type="created"></tile></div>
		</div>
		
		<div class="row">                        
			<div class="col-md-12 table-responsive margem-topo">
				<table class="table table-bordered table-striped">
					<thead >
						<tr>
							<th class="text-center text-mes"></th>
							@foreach($tabPrevistoRealizado['labels'] as $tabMes)										
								<th class="text-center" colspan="2">{{ $tabMes}}</th>									
							@endforeach
						</tr>
						<tr>
							<th class="text-center"></th>
							<?php 
																
								for ($i = 1; $i <= count($tabPrevistoRealizado['labels']); $i++) {
									?>
									<th class="text-center">% Mensal</th>
									<th class="text-center">% Acum.</th>
									<?
								}
							?>
						</tr>						
					</thead>
					<tbody>																					
						<tr>
							<td class="text-center text-mes">PDP</td>
							@foreach($tabPrevistoRealizado['data']['planoDiretorAcumulado'] as $planoDiretorAcumulado)										
								<td class="text-center" style="color:black;font-weight:bold;">{{ $planoDiretorAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TRABALHO</td>
							@foreach($tabPrevistoRealizado['data']['planoTrabalhoAcumulado'] as $planoTrabalhoAcumulado)										
								<td class="text-center" style="color:black;font-weight:bold;">{{ $planoTrabalhoAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TENDÊNCIA REAL</td>
							@foreach($tabPrevistoRealizado['data']['planoPrevistoAcumulado'] as $planoPrevistoAcumulado)										
								<td class="text-center">{{ $planoPrevistoAcumulado.'%'}}</td>																											
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TENDÊNCIA TRABALHO</td>
							@foreach($tabPrevistoRealizado['data']['planoRealizadoAcumulado'] as $planoRealizadoAcumulado)										
								<td class="text-center">{{ $planoRealizadoAcumulado.'%'}}</td>																											
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
				
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Curva de Evolução Física" type="created"></tile></div>
		</div>
		
	</div>
</div>
