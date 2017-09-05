@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Atualização de Contratos
        </h1>
    </section>
    <div class="content">
        {!! Form::open() !!}
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('obra_id', 'Obra:') !!}
                    {!! Form::select('obra_id[]', $obras, null,
                    ['class' => 'form-control select2', 'required'=>'required', 'multiple'=>'multiple']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
                    {!! Form::select('fornecedor_id', ['' => 'Escolha...'],
                    old('fornecedor_id'),
                    [
                        'class' => 'form-control select2',
                        'id'=>'fornecedor_id'
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('insumo_id', 'Insumo:') !!}
                    {!! Form::select('insumo_id', ['' => 'Escolha...'],
                    old('insumo_id'),
                    [
                        'class' => 'form-control select2',
                        'id'=>'insumo_id'
                    ]) !!}
                </div>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Valor Atual</th>
                        <th>Novo Valor</th>
                        <th width="5%"></th>
                    </tr>
                    </thead>
                    <tbody id="insumos_body">

                    </tbody>
                </table>

            </div>
            <div class="box-footer">

                <a href="{!! route('contratos.index') !!}" class="btn btn-default btn-flat btn-lg">
                    <i class="fa fa-arrow-left"></i> {{ ucfirst( trans('common.back') )}}
                </a>
                <button type="submit" class="btn btn-success btn-flat btn-lg pull-right">
                    <i class="fa fa-save"></i> Atualizar
                </button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var linhas = 0;
        $(function () {
            // Colocar OnChange na Obra buscar Fornecedores com contratos
            $('select[name="obra_id[]"]').on('change', function (evt) {
                var array_obras = $(evt.target).val();
                if(!array_obras){
                    $('select[name="fornecedor_id"]').html('');
                    $('select[name="fornecedor_id"]').trigger('change.select2');

                    $('select[name="insumo_id"]').html('');
                    $('select[name="insumo_id"]').trigger('change.select2');
                    $('#insumos_body').html('');

                    return false;
                }
                $.ajax('/contratos/fornecedores-por-obras', {
                    data: {
                        obras: array_obras
                    }
                })
                        .done(function (retorno) {
                            var options_fornecedores = '<option value="" selected>-</option>';
                            $.each(retorno.data, function (index, valor) {
                                options_fornecedores += '<option value="' + valor.id + '">' + valor.nome + '</option>';
                            });
                            $('select[name="fornecedor_id"]').html(options_fornecedores);
                            $('select[name="fornecedor_id"]').trigger('change.select2');

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
            $('select[name="fornecedor_id"]').on('change', function (evt) {
                var fornecedor_id = $(evt.target).val();
                $('select[name="insumo_id"]').html('');
                $('select[name="insumo_id"]').trigger('change.select2');
                $('#insumos_body').html('');

                if(!fornecedor_id){
                    return false;
                }
                $.ajax('/contratos/insumos-por-fornecedor', {
                    data: {
                        fornecedor: fornecedor_id,
                        obras: $('select[name="obra_id[]"]').val()
                    }
                })
                        .done(function (retorno) {
                            var options_insumos = '<option value="" selected>-</option>';
                            $.each(retorno.data, function (index, valor) {
                                options_insumos += '<option value="' + valor.contrato_item_id + '">' + valor.nome + '</option>';
                            });
                            $('select[name="insumo_id"]').html(options_insumos);
                            $('select[name="insumo_id"]').trigger('change.select2');

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
            $('select[name="insumo_id"]').on('change', function (evt) {
                var insumo_id = $(evt.target).val();
                $.ajax('/contratos/insumo-valor', {
                    data: {
                        insumo: insumo_id
                    }
                })
                        .done(function (retorno) {
                            linhas++;
                            $('#tr_insumo_'+retorno.insumo.id).remove();
                            var linha = '<tr id="tr_insumo_'+retorno.insumo.id+'">';

                            linha += '<td align="left">'+retorno.insumo.nome+'</td>';
                            linha += '<td align="right">'+ floatToMoney(retorno.valor_unitario) +'</td>';
                            linha += '<td>' +
                                    '   <div class="input-group">' +
                                    '       <span class="input-group-addon">R$</span>' +
                                    '       <input type="text"' +
                                    '           class="form-control input-sm" ' +
                                    '           value="" required="required" ' +
                                    '           name="valor_unitario['+retorno.insumo.id+']" ' +
                                    '           id="valor_unitario_'+retorno.insumo.id+'">' +
                                    '   </div>' +
                                    '</td>' ;
                            linha += '<td>' +
                                    '   <button type="button" onclick="removerLinha('+retorno.insumo.id+')" class="btn btn-flat btn-sm btn-danger">' +
                                    '       <i class="fa fa-times"></i>' +
                                    '   </button>' +
                                    '</td>';
                            linha += '</tr>';

                            $('#insumos_body').append(linha);

                            $('#valor_unitario_'+retorno.insumo.id).maskMoney({allowNegative: true, thousands:'.', decimal:','});

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

        });
        function removerLinha(qual) {
            $('#tr_insumo_'+qual).remove();
        }
    </script>
@stop
