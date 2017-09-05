@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Sugestão de Valor
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
            
        });
        function removerLinha(qual) {
            $('#tr_insumo_'+qual).remove();
        }
    </script>
@stop
