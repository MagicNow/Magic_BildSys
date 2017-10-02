<?php  
	$valor_medido_total = $medicaoFisica['attributes']['valor_medido_total'] ;
	$valor_medido = $medicaoFisica['attributes']['valor_medido_total'] ;
?>

<!-- Valor Medido Total Field -->
<div class="form-group col-sm-2">
    {!! Form::label('valor_medido_total', 'Valor Total já Medido:') !!}
    {!! Form::text('valor_medido_total', $valor_medido_total.'%', ['class' => 'form-control moneyToFloat orange', 'required', 'readonly']) !!}
</div>

<!-- Valor Medido Field -->
<div class="form-group col-sm-2">
    {!! Form::label('valor_medido', 'Nova Medição:') !!}
    {!! Form::text('valor_medido', null, ['class' => 'form-control moneyToFloat', 'required']) !!}
</div>

<!-- Período Inicio Field -->
<div class="form-group col-sm-3">
    {!! Form::label('periodo_inicio', 'Data início:') !!}
    {!! Form::date('periodo_inicio', null, ['class' => 'form-control' , 'required']) !!}
</div>

<!-- Período Fim Field -->
<div class="form-group col-sm-3">
    {!! Form::label('periodo_termino', 'Data Fim:') !!}
    {!! Form::date('periodo_termino', null, ['class' => 'form-control' , 'required']) !!}
</div>

<!-- Observação Field -->
<div class="form-group col-sm-6">
    {!! Form::label('observacao', 'Observação da Medição:') !!}
    {!! Form::textarea('observacao', null, ['class' => 'form-control', 'id'=>'obs', 'rows'=>4]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.medicao_fisicas.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>


@section('scripts')
    <script type="text/javascript">
               
        $(function () { 	
						
			$("#valor_medido").on('change', function (evt) {

				valorMedido = parseFloat($(this).val());
				valorMedidoTotal = parseFloat($("#valor_medido_total").val());
				valorMedidoDiff = 100 - valorMedido - valorMedidoTotal;

				if (valorMedido >= 100.00){
					swal({
						title: 'Ops!',
						text: 'O limite é 100%',
						type: 'error',
					});
					$(this).val('100,00');
				}

				if (valorMedidoDiff < 0.00){
					swal({
						title: 'Ops!',
						text: 'Essa tarefa já tem medição de 100%',
						type: 'error',
					});
					$(this).val('');
				}

			}); 
			
			$("#periodo_inicio , #periodo_termino").on('change', function (evt) {
												
				var dateObj1 = new Date($('#periodo_inicio').val());
				var dateObj2 = new Date($('#periodo_termino').val());

				if(dateObj1.getTime() > dateObj2.getTime()){
					swal({
						title: 'Ops!',
						text: 'Período Inválido, a data de término tem que ser maior que a data de ínicio',
						type: 'error',
					});
					
					$('#periodo_inicio').val("");
					$('#periodo_termino').val("");					
				}  
				
			});
		  
			

        });
    </script>
@stop