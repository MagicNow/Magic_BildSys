@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h3>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Nova medição
        </h3>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'medicoes.create', 'method'=>'get']) !!}

                    <div class="form-group col-md-6">
                        {!! Form::label('obra_id', 'Obra:') !!}
                        {!! Form::select('obra_id',[''=>'Selecione']+$obras, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'obra_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('contrato_id', 'Contrato:') !!}
                        {!! Form::select('contrato_id',[], null, ['class' => 'form-control select2','required'=>'required' , 'id'=>'contrato_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('tarefa_id', 'Tarefa:') !!}
                        {!! Form::select('tarefa_id',[], null, ['class' => 'form-control select2', 'id'=>'tarefa_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('contrato_item_apropriacao_id', 'Insumo:') !!}
                        {!! Form::select('contrato_item_apropriacao_id',[], null, ['class' => 'form-control select2','required'=>'required', 'id'=>'contrato_item_apropriacao_id']) !!}
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-flat btn-lg">
                            <i class="fa fa-check" aria-hidden="true"></i> Medir
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var obra_id = null;
        var contrato_id = null;
        var tarefa_id = null;
        var contrato_id = null;
        var contrato_item_apropriacao_id = null;
        function buscaContratos() {
            if (!obra_id) {
                $('#contrato_id').html('');
                $('#contrato_id').trigger('change.select2');
            }
            $.ajax('/medicoes/contratos-por-obra', {
                data: {
                    obra: obra_id
                }
            })
                    .done(function (retorno) {
                        var options_contratos = '<option value="" selected>-</option>';
                        if(retorno.data){
                            $.each(retorno.data, function (index, valor) {
                                options_contratos += '<option value="' + valor.id + '">' + valor.id + ' | '  + valor.fornecedor.nome + '</option>';
                            });
                        }
                        $('#contrato_id').html(options_contratos);
                        $('#contrato_id').trigger('change.select2');

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

        function buscaTarefas(){
            if (!obra_id) {
                $('#tarefa_id').html('');
                $('#tarefa_id').trigger('change.select2');
            }
            $.ajax('/medicoes/tarefas-por-obra', {
                data: {
                    obra: obra_id,
                    contrato: contrato_id
                }
            })
                    .done(function (retorno) {
                        var options_tarefas = '<option value="" selected>-</option>';
                        if(retorno.data){
                            $.each(retorno.data, function (index, valor) {
                                options_tarefas += '<option value="' + valor.id + '"'+(tarefa_id == valor.id? ' selected="selected" ': '' )+'>' + valor.tarefa + '</option>';
                            });
                            $('#tarefa_id').html(options_tarefas);
                            $('#tarefa_id').trigger('change.select2');
                        }


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

        function buscaInsumos(){
            if (!obra_id) {
                $('#contrato_item_apropriacao_id').html('');
                $('#contrato_item_apropriacao_id').trigger('change.select2');
            }

            $.ajax('/medicoes/insumos', {
                data: {
                    contrato: contrato_id,
                    tarefa: tarefa_id,
                    obra: obra_id
                }
            })
                    .done(function (retorno) {
                        var options_insumos = '<option value="" selected>-</option>';
                        if(retorno.data){
                            $.each(retorno.data, function (index, valor) {
                                options_insumos += '<option value="' + valor.id + '"'+(contrato_item_apropriacao_id == valor.id? ' selected="selected" ': '' )+'>' + valor.nome + '</option>';
                            });
                        }

                        $('#contrato_item_apropriacao_id').html(options_insumos);
                        $('#contrato_item_apropriacao_id').trigger('change.select2');

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

        $(function () {
            // Colocar OnChange na Obra buscar Fornecedores com contratos
            $('#obra_id').on('change', function (evt) {
                var v_obra = $(evt.target).val();
                obra_id = v_obra;

                buscaContratos();

                buscaTarefas();

                buscaInsumos();
            });
            // Na hora q selecionar o Fornecedor trazer os insumos q este contrato tem contrato
            $('#contrato_id').on('change', function (evt) {
                contrato_id = $(evt.target).val();
                buscaInsumos();
                buscaTarefas();
            });

            $('#tarefa_id').on('change', function (evt) {
                tarefa_id = $(evt.target).val();
                buscaInsumos();
            });

            $('#contrato_item_apropriacao_id').on('change', function (evt) {
                contrato_item_apropriacao_id = $(evt.target).val();
            });

        });
    </script>
@stop