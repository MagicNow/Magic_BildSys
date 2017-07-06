@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <span class="pull-left title">
                   <h3>
                       <button type="button" class="btn btn-link" onclick="history.go(-1);">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                       </button>
                       <span>Criar previsão de memória de cálculo</span>
                   </h3>
                </span>
            </div>
        </div>
    </section>

    {!! Form::model($contrato, ['route' => ['contratos.memoria_de_calculo_salvar']]) !!}
    <div class="content">
        <div class="clearfix"></div>

        <input type="hidden" name="insumo_id" value="{{$insumo->id}}">
        <input type="hidden" name="unidade_sigla" value="{{$insumo->unidade_sigla}}">
        <input type="hidden" name="contrato_item_apropriacao_id" value="{{$contrato_item_apropriacao->id}}">
        <input type="hidden" name="contrato_item_id" value="{{$contrato_item_apropriacao->contrato_item_id}}">

        <div class="form-group col-md-2">
            {!! Form::label('contrato', 'Contrato:') !!}
            <p class="form-control">{!! $contrato->id !!}</p>
        </div>

        <div class="form-group col-md-4">
            {!! Form::label('fornecedor', 'Fornecedor:') !!}
            <p class="form-control">{!! $contrato->fornecedor->nome !!}</p>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('insumo', 'Insumo:') !!}
            <p class="form-control">{!! $contrato_item_apropriacao->codigo_insumo . ' - ' . $insumo->nome . ' - ' . $insumo->unidade_sigla!!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('planejamento_id', 'Tarefa:') !!}
            {!! Form::select('planejamento_id', $tarefas , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('obra_torre_id', 'Torres:') !!}
            {!! Form::select('obra_torre_id', $obra_torres , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('memoria_de_calculo', 'Memória de cálculo:') !!}
            <a href="/memoriaCalculos/create"
               class="btn btn-flat btn-sm btn-primary pull-right"
               data-toggle="tooltip"
               data-placement="top"
               title="Criar memória de cálculo"
               style="margin-top: -10px;">
                <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
            </a>
            {!! Form::select('memoria_de_calculo', $memoria_de_calculo , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>

        <table class="table table-striped table-no-margin">
            <thead>
            <tr>
                <th>Torre</th>
                <th>Estrutura</th>
                <th>Pavimento</th>
                <th>Trecho</th>
                <th style="width: 15%;">Data</th>
                <th style="width: 15%;">Qtde</th>
                <th style="width: 15%;">%</th>
                <th style="width: 4%;"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="col-sm-12" style="margin-top: 10px;">
            <button class="btn btn-success pull-right flat" type="submit">
                <i class="fa fa-save"></i> Salvar
            </button>

            <button class="btn btn-default flat" onclick="history.go(-1);">
                <i class="fa fa-times"></i> Cancelar
            </button>
        </div>

    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    var count = 0;

    // Função para adicionar linha na tabela
    function adicionarNaTabela(memoria_calculo_bloco_id) {
        count ++;
        $('tbody').append('\
        <tr id=linha_'+count+'>\
            <input type="hidden" name="itens['+count+'][memoria_calculo_bloco_id]" value="'+memoria_calculo_bloco_id+'">\
            <td>\
                torre 1\
            </td>\
            <td>\
                Pre Tipo\
            </td>\
            <td>\
                SS 1\
            </td>\
            <td>\
                Trecho 1\
            </td>\
            <td>\
            <input type="date" class="form-control" name="itens['+count+'][data_competencia]">\
            </td>\
            <td>\
                <input type="text" class="form-control money" name="itens['+count+'][qtd]">\
            </td>\
            <td>\
                <input type="text" class="form-control money">\
            </td>\
            <td>\
            <button onclick="removerLinha('+count+');" class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Remover">\
                <i class="fa fa-remove fa-fw" aria-hidden="true"></i>\
            </td>\
        </tr>\
        ');

        $('.money').maskMoney({
            allowNegative: true,
            thousands: '.',
            decimal: ','
        });
    }

    // Função para remover linha da tabela
    function removerLinha(id) {
        $('#linha_'+id).remove();
    }
</script>
@endsection