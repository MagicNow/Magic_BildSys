<!-- Obras Field -->
<div class="form-group col-md-6">
	{!! Form::label('obra_id', 'Obra:') !!}
	{!! Form::select('obra_id',[''=>'Selecione']+$obras, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'obra_id']) !!}
</div>

<!-- Tarefas Field -->
<div class="form-group col-md-6">
	{!! Form::label('tarefa_id', 'Tarefa:') !!}
	{!! Form::select('tarefa',[], null, ['class' => 'form-control select2','required'=>'required' , 'id'=>'tarefa_id']) !!}
</div>

<!-- Valor Medido Field -->
<div class="form-group col-sm-4">
    {!! Form::label('valor_medido_total', 'Valor da Medição:') !!}
    {!! Form::text('valor_medido_total', null, ['class' => 'form-control moneyToFloat', 'required']) !!}
</div>

<!-- Período Inicio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('periodo_inicio', 'Período início:') !!}
    {!! Form::date('periodo_inicio', null, ['class' => 'form-control' , 'required']) !!}
</div>

<!-- Período Fim Field -->
<div class="form-group col-sm-4">
    {!! Form::label('periodo_termino', 'Período Fim:') !!}
    {!! Form::date('periodo_termino', null, ['class' => 'form-control' , 'required']) !!}
</div>

<!-- Observação Field -->
<div class="form-group col-sm-12">
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
        
		var obra_id = null;
        var tarefa_id = null;
        
        function buscaTarefas() {
			
			startLoading();	
            
			if (!obra_id) {
                $('#tarefa_id').html('');
                $('#tarefa_id').trigger('change.select2');
            }
			
            $.ajax('/admin/medicao_fisicas/tarefas-por-obra', {
                data: {
                    obra_id: obra_id
                }
            })
                    .done(function (retorno) {
												
                        var options_tarefas = '<option value="" selected>-</option>';
												
                        if(retorno){
                            $.each(retorno, function (index, valor) {
                                //options_tarefas += '<option value="' + valor.id + '">' + valor.id + ' | '  + valor.tarefa + '</option>';
								options_tarefas += '<option value="' + valor.tarefa + '">'+ valor.tarefa + '</option>';
                            });
                        }
                        $('#tarefa_id').html(options_tarefas);
                        $('#tarefa_id').trigger('change.select2');

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
       
        $(function () {
			
            // Colocar OnChange na Obra buscar por tarefas
            $('#obra_id').on('change', function (evt) {
                var v_obra = $(evt.target).val();
                obra_id = v_obra;
                buscaTarefas();
            });
		
						
			$("#valor_medido_total").on('change', function (evt) {
				
				var valorMedido = parseFloat($(this).val());
				
				//console.log(valorMedido);
				
				if (valorMedido >= 100.00){
					swal({
						title: 'Ops!',
						text: 'O limite é 100%',
						type: 'error',
					});
					$(this).val('100,00');
				}
			}); 
			  
			$("#periodo_inicio , #periodo_termino").on('change', function (evt) {
												
				var dateObj1 = new Date($('#periodo_inicio').val());
				var dateObj2 = new Date($('#periodo_termino').val());

				if(dateObj1.getTime() > dateObj2.getTime()){
					swal({
						title: 'Ops!',
						text: 'A data de término tem que ser maior que a data de ínicio',
						type: 'error',
					});
					
					$('#periodo_inicio').val("");
					$('#periodo_termino').val("");					
				}  
				
			});	

        });
    </script>
@stop