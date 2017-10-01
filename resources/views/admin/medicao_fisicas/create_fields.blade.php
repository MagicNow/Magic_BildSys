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
<div class="form-group col-sm-2">
    {!! Form::label('valor_medido', 'Valor da Medição:') !!}
    {!! Form::text('valor_medido', null, ['class' => 'form-control moneyToFloat', 'required' , 'maxlength' => '12']) !!}
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
                    obra: obra_id
                }
            })
                    .done(function (retorno) {
						
						//console.log(retorno.data);
						
                        var options_tarefas = '<option value="" selected>-</option>';
												
                        if(retorno.data){
                            $.each(retorno.data, function (index, valor) {
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
		
						
			$("#valor_medido").on('change', function (evt) {
				
				valorMedido = parseFloat($(this).val());
				
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
					  
					

        });
    </script>
@stop