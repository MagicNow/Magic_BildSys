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
        <div class="col-md-6 form-group">
            {!! Form::label('', 'Fornecedor') !!}
            <p class="form-control input-lg">
                @if($contrato->has_material_faturamento_direto)
                <label class="radio-inline">
                    <input type="radio"
                        checked
                        name="insumo"
                        value="contratada"
                        data-container=".js-table-container"
                        class="js-view-selector js-fornecedor-selector">
                        Do Contrato:
                        <span class="highlight">
                            {{ $contrato->fornecedor->nome }}
                        </span>
                </label>
                <label class="radio-inline">
                    <input type="radio"
                        name="insumo"
                        data-container=".js-table-container"
                        value="direto"
                        class="js-view-selector js-fornecedor-selector">
                        Outro
                </label>
                @else
                    <label class="radio-inline">
                        <input type="radio"
                            checked
                            name="insumo"
                            value="contratada"
                            data-container=".js-table-container"
                            class="js-view-selector js-fornecedor-selector no-icheck
                            hidden">
                            <span class="highlight">
                                {{ $contrato->fornecedor->nome }}
                            </span>
                    </label>
                @endif
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
                        data-container="#tables-container"
                        class="js-view-selector">
                    Insumos
                </label>
                <label class="radio-inline">
                    <input type="radio"
                        name="view"
                        value="apropriacoes"
                        data-container="#tables-container"
                        class="js-view-selector">
                    Apropriações
                </label>
            </p>
        </div>
    </div>
    <div class="row hidden" id="fornecedor-selector">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Fornecedor da Solicitação</label>
                {!!
                    Form::select(
                        'fornecedor_id',
                        [],
                        null,
                        ['id' => 'fornecedor_id', 'class' => 'form-control', 'data-ignore' => $contrato->fornecedor->id]
                    )
                !!}
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-normal-table">
        <div class="panel-body" id="tables-container">
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
