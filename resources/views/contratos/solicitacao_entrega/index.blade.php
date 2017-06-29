@extends('layouts.front')

@section('content')
<div class="content-header">
    <h1 class="content-header-title">
        Solicitação de Entrega
    </h1>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-2 form-group">
            {!! Form::label('', 'Código do Contrato') !!}
            <p class="form-control input-lg highlight text-center">
                {!! $contrato->id !!}
            </p>
        </div>
        <div class="col-md-4 form-group">
            {!! Form::label('', 'Fornecedor') !!}
            <p class="form-control input-lg">
                {!! $contrato->fornecedor->nome !!}
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

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="js-table-container" data-view-name="apropriacoes" style="display: none">
                @include('contratos.solicitacao_entrega.table_apropriacoes')
            </div>
            <div class="js-table-container" data-view-name="insumos">
                @include('contratos.solicitacao_entrega.table_insumos')
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-flat btn-info pull-right">
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
@endsection

@section('scripts')
    <script src="{{ asset('js/solicitacao-de-entrega.js') }}"> </script>
@endsection
