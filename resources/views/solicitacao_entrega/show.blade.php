@extends('layouts.front')

@section('content')
<div class="content-header">
    <h1 class="content-header-title">
        Solicitação de Entrega #{{ $entrega->id }}
    </h1>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-2 form-group">
            {!! Form::label('', 'Código do Contrato') !!}
            <p class="form-control input-lg highlight text-center">
                {!! $entrega->contrato->id !!}
            </p>
        </div>
        <div class="col-md-4 form-group">
            {!! Form::label('', 'Fornecedor') !!}
            <p class="form-control input-lg">
                {!! $entrega->contrato->fornecedor->nome !!}
            </p>
        </div>
        <div class="col-md-4 form-group">
            {!! Form::label('', 'Visão') !!}
            <p class="form-control input-lg">
                <label class="radio-inline">
                    <input type="radio"
                        checked
                        name="view"
                        value="insumos"
                        class="js-view-selector">
                    Insumos
                </label>
                <label class="radio-inline">
                    <input type="radio"
                        name="view"
                        value="apropriacoes"
                        class="js-view-selector">
                    Apropriações
                </label>
            </p>
        </div>
    </div>

    <div class="panel panel-default panel-normal-table">
        <div class="panel-body">
            <div class="js-table-container" data-view-name="apropriacoes" style="display: none">
                @include(
                    'contratos.solicitacao_entrega.table_apropriacoes',
                    [
                        'apropriacoes' => $entrega->contrato
                            ->itens
                            ->pluck('apropriacoes')
                            ->collapse()
                    ]
                )
            </div>
            <div class="js-table-container" data-view-name="insumos">
                @include(
                    'contratos.solicitacao_entrega.table_insumos',
                    ['itens' => $entrega->contrato->itens]
                )
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit"
                        class="btn btn-flat btn-info pull-right"
                        id="finalizar">
                        Finalizar
                    </button>
                    <div class="col-sm-3 pull-right h4 text-right">
                        Total Solicitado:
                        <span id="total-container">
                            R$ 0,00
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('contratos.solicitacao_entrega.modal_selecionar_insumo')
@endsection

@section('scripts')
    <script src="{{ asset('js/solicitacao-de-entrega.js') }}"> </script>
@endsection
