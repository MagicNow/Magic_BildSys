@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Nova Medicão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'medicoes.create', 'method'=>'get']) !!}

                    <div class="form-group col-md-6">
                        {!! Form::label('obra_id', 'Obra:') !!}
                        {!! Form::select('obra_id',$obras, null, ['class' => 'form-control select2','required'=>'required', 'id'=>'obra_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
                        {!! Form::select('fornecedor_id',[], null, ['class' => 'form-control select2','required'=>'required', 'id'=>'fornecedor_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('servico_id', 'Serviço:') !!}
                        {!! Form::select('servico_id',[], null, ['class' => 'form-control select2','required'=>'required', 'id'=>'servico_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('contrato_id', 'Contrato:') !!}
                        {!! Form::select('contrato_id',[], null, ['class' => 'form-control select2','required'=>'required', 'id'=>'contrato_id']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('insumo_id', 'Insumo:') !!}
                        {!! Form::select('insumo_id',[], null, ['class' => 'form-control select2','required'=>'required', 'id'=>'insumo_id']) !!}
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
        var fornecedor_id = null;
        var servico_id = null;
        var contrato_id = null;
        var insumo_id = null;
        function buscaFornecedores() {
            if (!obra_id) {
                $('#fornecedor_id').html('');
                $('#fornecedor_id').trigger('change.select2');
            }
            $.ajax('/medicoes/fornecedores-por-obra', {
                data: {
                    obra: obra_id,
                    servico: servico_id,
                    contrato: contrato_id,
                    insumo: insumo_id
                }
            })
                    .done(function (retorno) {
                        var options_fornecedores = '<option value="" selected>-</option>';
                        $.each(retorno.data, function (index, valor) {
                            options_fornecedores += '<option value="' + valor.id + '">' + valor.nome + '</option>';
                        });
                        $('#fornecedor_id').html(options_fornecedores);
                        $('#fornecedor_id').trigger('change.select2');

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
                obra_id = v_obra
                :

                if (!v_obra) {


                    $('#contrato_id').html('');
                    $('#contrato_id').trigger('change.select2');

                    $('#insumo_id').html('');
                    $('#insumo_id').trigger('change.select2');

                    return false;
                }
                buscaFornecedores();
                $.ajax('/medicoes/contratos-por-obra', {
                    data: {
                        obra: v_obra
                    }
                })
                        .done(function (retorno) {
                            var options_contratos = '<option value="" selected>-</option>';
                            $.each(retorno.data, function (index, valor) {
                                options_contratos += '<option value="' + valor.id + '">' + valor.id + ' | ' + floatToMoney(parseFloat(valor.valor_total_atual)) + ' | ' + valor.fornecedor.nome + '</option>';
                            });
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

                $.ajax('/medicoes/servicos-por-obra', {
                    data: {
                        obra: v_obra
                    }
                })
                        .done(function (retorno) {
                            var options_servicos = '<option value="" selected>-</option>';
                            $.each(retorno.data, function (index, valor) {
                                options_servicos += '<option value="' + valor.id + '">' + valor.nome + '</option>';
                            });
                            $('#servico_id').html(options_servicos);
                            $('#servico_id').trigger('change.select2');

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
            });
            // Na hora q selecionar o Fornecedor trazer os insumos q este fornecedor tem contrato
            $('#fornecedor_id').on('change', function (evt) {
                var fornecedor_id = $(evt.target).val();
                $('#insumo_id').html('');
                $('#insumo_id').trigger('change.select2');

                if (!fornecedor_id) {
                    return false;
                }
                $.ajax('/medicoes/insumos-por-fornecedor', {
                    data: {
                        fornecedor: fornecedor_id,
                        obras: $('#obra_id').val()
                    }
                })
                        .done(function (retorno) {
                            var options_insumos = '<option value="" selected>-</option>';
                            $.each(retorno.data, function (index, valor) {
                                options_insumos += '<option value="' + valor.contrato_item_id + '">' + valor.nome + '</option>';
                            });
                            $('#insumo_id').html(options_insumos);
                            $('#insumo_id').trigger('change.select2');

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
            });
            // ao escolher o insumo adicionar na tabela e habilitar postagem
            $('#insumo_id').on('change', function (evt) {
                var insumo_id = $(evt.target).val();

            });

        });
    </script>
@stop