@extends('layouts.front')

@section('content')
<div class="content-header">
    <h1 class="content-header-title">
        <button type="button" class="btn btn-link" onclick="history.go(-1);">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
        Solicitação de entrega #{{ $entrega->id }}
        @include('solicitacao_entrega.actions')
        <a href="{{ route('solicitacao-entrega.imprimirSolicitacaoEntrega', $entrega->id) }}" download="solicitacao-entrega_{{ $entrega->id }}.pdf" target="_blank"
           class="btn btn-lg btn-flat btn-success pull-right" title="Baixar Solicitação de entrega">
            <i class="fa fa-print"></i>
        </a>
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
                {!!
                    $entrega->fornecedor_id
                        ? $entrega->fornecedor->nome
                        : $entrega->contrato->fornecedor->nome
                !!}
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
        <div class="col-md-2 form-group">
            {!! Form::label('', 'Status') !!}
            <p class="form-control input-lg text-center">
                <span class="label label-white lb-md">
                    <i class="fa fa-circle" style="color: {{ $entrega->status->cor }}"></i>
                    {{ $entrega->status->nome }}
                </span>
            </p>
        </div>

        @if($entrega->anexo)
            <div class="col-md-4 form-group">
                <label>Anexo</label>
                <p class="form-control input-lg">
                    <a href="{!! Storage::url($entrega->anexo) !!}" download="">Baixar anexo</a>
                </p>
            </div>
        @endif
    </div>

    <div class="panel panel-default panel-normal-table">
        <div class="panel-body">
            <div class="js-table-container" data-view-name="apropriacoes" style="display: none">
                @include('solicitacao_entrega.table_apropriacoes')
            </div>
            <div class="js-table-container" data-view-name="insumos">
                @include('solicitacao_entrega.table_insumos')
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-3 pull-right h4 text-right">
                        Total Solicitado:
                        <span id="total-container">
                            {{ float_to_money($entrega->total) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden">
    {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
</div>
@endsection

@section('scripts')
    <script>
        window.options_motivos = document.getElementById('motivo').innerHTML;
    </script>
    <script src="{{ asset('js/solicitacao-de-entrega.js') }}"> </script>
@endsection
