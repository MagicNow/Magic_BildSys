<div class="row">	
	<div class="col-xs-12">               			
		
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Previsto x Realizado" type="created"></tile></div>
		</div>
		
		<!-- Previsto x Realizado -->
		<div class="row">                        
			<div class="col-md-12 table-responsive margem-topo">
				<table class="table table-bordered table-striped">
					<thead >
						<tr>
							<th class="text-center" style="background-color:white; color:white;border:none;"></th>
							@foreach($tabPrevistoRealizado['labels'] as $tabMes)										
								<th class="text-center" colspan="2" style="background-color:orange; color:white;">{{ $tabMes}}</th>									
							@endforeach
						</tr>
						<tr>
							<th class="text-center" style="background-color:white; color:white;border:none;"></th>
							<?php 
																
								for ($i = 1; $i <= count($tabPrevistoRealizado['labels']); $i++) {
									?>
									<th class="text-center">% Mensal</th>
									<th class="text-center">% Acum.</th>
									<?php
								}
							?>
						</tr>						
					</thead>
					<tbody>																					
						<tr>
							<td class="text-center text-mes">PDP</td>
							@foreach($tabPrevistoRealizado['data']['pdp'] as $pdp)																	
								<td class="text-center">{{ $pdp['mensal'].'%'}}</td>
								<td class="text-center">{{ $pdp['acumulado'].'%'}}</td>																
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TRABALHO</td>
							@foreach($tabPrevistoRealizado['data']['trabalho'] as $trabalho)										
								<td class="text-center">{{ $trabalho['mensal'].'%'}}</td>
								<td class="text-center">{{ $trabalho['acumulado'].'%'}}</td>								
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TENDÊNCIA REAL</td>
							@foreach($tabPrevistoRealizado['data']['tendencia-real'] as $tendenciaReal)										
								<td class="text-center">{{ $tendenciaReal['mensal'].'%'}}</td>
								<td class="text-center">{{ $tendenciaReal['acumulado'].'%'}}</td>								
							@endforeach
						</tr>
						
						<tr>
							<td class="text-center text-mes">TENDÊNCIA TRABALHO</td>
							@foreach($tabPrevistoRealizado['data']['tendencia-trabalho'] as $tendenciaTrabalho)										
								<td class="text-center">{{ $tendenciaTrabalho['mensal'].'%'}}</td>
								<td class="text-center">{{ $tendenciaTrabalho['acumulado'].'%'}}</td>								
							@endforeach
						</tr>
						
					</tbody>
				</table>
			</div> 
		</div>
		
		<!-- Assertividade Mensal -->
		<div class="row">                        
			<div class="col-md-12 table-responsive margem-topo">
				<table class="table table-bordered table-striped">
					<thead >
						<tr>
							<th class="text-center" colspan="5" style="background-color:orange; color:white;">ASSERTIVIDADE MENSAL</th>							
						</tr>
						<tr>
							<th class="text-center">PDP</th>
							<th class="text-center">TRABALHO</th>
							<th class="text-center">PREVISTO</th>
							<th class="text-center">REAL</th>
							<th class="text-center">ASSERTIVIDADE</th>							
						</tr>						
					</thead>
					<tbody>																					
						<tr>
							@foreach($assertividadeMensal['mensal'] as $percentualAssertividade)																	
								<td class="text-center">{{ $percentualAssertividade.'%'}}</td>																						
							@endforeach
						</tr>						
					</tbody>
				</table>
			</div> 
		</div>
		
		<!-- Gráfico Previsto x Realizado -->		
		<div class="row">                       			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">% Mensal</div>
					<div class="element-previsto-realizado-sem">                                   
						<chartjs-bar 
									 :labels="labelsPrevistoXRealizadoMes" 
									 :datasets="datasetsPrevistoXRealizadoMes"                                                                                                
									 :option="myoptionPrevistoXRealizadoMes"
									 :height="220">
						</chartjs-bar>
					</div>
				</div>
			</div>
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">% Acumulada</div>
					<div class="element-pdp-ptrab-realac">                                    
						<chartjs-bar 
									 :labels="labelsPrevistoXRealizadoAcu" 
									 :datasets="datasetsPrevistoXRealizadoAcu"
									 :option="myoptionPrevistoXRealizadoAcu"
									 :height="220">
						</chartjs-bar>
					</div>
				</div>
			</div>
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">DESVIO PDP</div>
					<div class="element-desvio-pdp">                                   
						<chartjs-bar 
							:labels="labelsDesvioPDP" 
							:datasets="datasetsDesvioPDP" 
							:scalesdisplay="false" 
							:option="myoptionDesvioPDP" 
							:height="220">
						</chartjs-bar>
					</div>
				</div>
			</div>
			
			<div class="col-md-3 margem-topo">							
				<div class="element-grafico">
					<div class="element-head">DESVIO TRABALHO</div>
					<div class="element-desvio-ptrab">                                    
						<chartjs-bar 
							:labels="labelsDesvioTrabalho" 
							:datasets="datasetsDesvioTrabalho" 
							:scalesdisplay="false" 
							:option="myoptionDesvioTrabalho" 
							:height="220">
						</chartjs-bar>
					</div>
				</div>
			</div>
			
			
		</div>			
								
		<div class="row">
			<div class="col-md-12 margem-topo"><tile title-color="head-grey" title="Curva de Evolução Física" type="created"></tile></div>
		</div>
		
	</div>
</div>
