@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
           Conciliação de Notas Fiscais
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">

                        <!-- Fornecedor Id Field -->
                        <div class="form-group col-sm-4">
                            {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
                            {!! Form::select('fornecedor_id',[''=>'Selecione...'] + (isset($fornecedoresArr) ? $fornecedoresArr : []), null, ['class' => 'form-control select2', 'id' => 'fornecedor_id']) !!}
                        </div>

                        <!-- Contrato Id Field -->
                        <div class="form-group col-sm-4">
                            {!! Form::label('contrato_id', 'Contrato:') !!}
                            {!! Form::select('contrato_id',[''=>'Selecione...'], null, ['class' => 'form-control select2', 'id' => 'contrato_id']) !!}
                        </div>

                        <!-- Notas Fiscais Id -->
                        <div class="form-group col-sm-4">
                            {!! Form::label('nota_fiscal_id', 'Nota Fiscal:') !!}
                            {!! Form::select('nota_fiscal_id',[''=>'Selecione...'], null, ['class' => 'form-control select2', 'id' => 'nota_fiscal_id']) !!}
                        </div>

                        <div class="col-sm-12">
                            <div class="pull-right">
                                <a href="#" onclick="iniciarConciliacao();" class="btn btn-success" >
                                    Iniciar Conciliação
                                </a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    $contratos = {!! json_encode($contratosArr) !!};
    $notas = {!! json_encode($notasFiscais) !!};

    $notasSelecionadas = [];
    $contratosSelecionados = [];

    $('#fornecedor_id').select2().on('change', function() {
        var fornecedor_id = $(this).val();
        carregaContratos(fornecedor_id);
        carregaNotasFiscais(fornecedor_id);
    });

    function parseData($fornecedorID, $tipo)
    {
        var $source = [];
        if ($tipo == 'notas') {
            $notasSelecionadas = $source = $notas[$fornecedorID];
        } else if ($tipo == 'contratos') {
            $contratosSelecionados = $source = $contratos[$fornecedorID];
        }

        if (!$source) {
            return [];
        }

        return $source;
    }

    function changeData(dataItem, $id) {
        var el = $('#' + $id);
        var temp = el.select2('val'); // save current value
        var newOptions = '<option value="">Selecione</option>';
        for (var $i in dataItem) {
            if (typeof dataItem[$i].id !== 'undefined') {
                newOptions += '<option value="' + dataItem[$i].id + '">' + dataItem[$i].text + '</option>';
            }
        }
        el.select2('destroy').html(newOptions).select2().select2('val', temp);
    }

    function carregaContratos(fornecedor_id)
    {
        var $data = parseData(fornecedor_id, 'contratos');
        changeData($data, 'contrato_id');
    }

    function carregaNotasFiscais(fornecedor_id)
    {
        var $data = parseData(fornecedor_id, 'notas');
        changeData($data, 'nota_fiscal_id');
    }

    function iniciarConciliacao() {

        var $fornecedor = $('#fornecedor_id');
        var $contrato = $('#contrato_id');
        var $nota_fiscal = $('#nota_fiscal_id');

        if ($.trim($fornecedor.val()) == '') {
            alert("Por favor selecione o Fornecedor.");
            return false;
        }

        if ($.trim($contrato.val()) == '') {
            alert("Por favor selecione o Contrato.");
            return false;
        }

        if ($.trim($nota_fiscal.val()) == '') {
            alert("Por favor selecione a Nota Fiscal.");
            return false;
        }

        window.location = "/notasfiscais/" + $nota_fiscal.val()
                + "/edit?fornecedor=" + $fornecedor.val()
                + "&contrato=" + $contrato.val();
    }
    </script>
@endsection