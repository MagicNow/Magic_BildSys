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

    <div class="content">
        <div class="clearfix"></div>

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

        <div class="form-group col-md-6">
            {!! Form::label('tarefa', 'Tarefa:') !!}
            {!! Form::select('tarefa', $tarefas , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
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

    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    var count = 0;

    // Função para adicionar linha na tabela
    function adicionarNaTabela() {
        count ++;
        $('tbody').append('\
        <tr id=linha_'+count+'>\
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
                {{Form::date('data_competencia', null, ['class' => 'form-control'])}}\
            </td>\
            <td>\
                {{Form::text('qtd', null, ['class' => 'form-control money'])}}\
            </td>\
            <td>\
                {{Form::text('percentual', null, ['class' => 'form-control money'])}}\
            </td>\
            <td>\
            <button onclick="removerLinha('+count+');"class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Remover">\
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