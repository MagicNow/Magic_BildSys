@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h3>            
            Exportar insumos/Estrutura
        </h3>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.pre_orcamentos.exportar_plan', 'method'=>'get']) !!}

						<!-- Máscara Padrao Field -->
						<div class="form-group col-md-6">
							{!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
							{!! Form::select('mascara_padrao_id',[''=>'Selecione']+$mascaraPadrao, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'mascara_padrao_id']) !!}
						</div>	

						<!-- Tipo de Orçamentos Field -->
						<div class="form-group col-md-6">
							{!! Form::label('tipo_orcamento_id', 'Tipo de Orçamentos:') !!}
							{!! Form::select('tipo_orcamento_id',[''=>'Selecione']+$tipoOrcamento, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'tipo_orcamento_id']) !!}
						</div>	

						
						<!-- Submit Field -->
						<div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-flat btn-lg">
                            <i class="fa fa-check" aria-hidden="true"></i> Exportar
                        </button>  
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var mascara_padrao_id = null;
        var tipo_orcamento_id = null;
        
        function buscaMascaraPadraoInsumos() {
			
            if (!mascara_padrao_id) {
                $('#mascara_padrao_id').html('');
                $('#mascara_padrao_id').trigger('change.select2');
            }
			
			if (!tipo_orcamento_id) {
                $('#tipo_orcamento_id').html('');
                $('#tipo_orcamento_id').trigger('change.select2');
            }
			
            /*$.ajax('/admin/medicao_fisicas/tarefas-por-obra', {
                data: {
                    obra: obra_id
                }
            })
                    .done(function (retorno) {
						
						console.log(retorno.data);
						
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
                    });*/
        }
       
        $(function () {
			
            // Colocar OnChange na Mascara Padrao buscar por tarefas
            $('#mascara_padrao_id').on('change', function (evt) {
                var v_mascara = $(evt.target).val();
                mascara_padrao_id = v_mascara;
                buscaMascaraPadrao();
            });
		
        });
    </script>
@stop
