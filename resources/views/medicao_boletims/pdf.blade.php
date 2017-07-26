@extends('layouts.printable')

@section('content')
    <div class="row">
        <div class="form-group col-md-6">
            {!! Form::label('obra', 'Obra:') !!}
            <div class="form-control">
                {{ $medicaoBoletim->obra->nome }}
            </div>
        </div>

        <!-- Contrato Id Field -->
        <div class="form-group col-md-6">
            {!! Form::label('contrato_id', 'Contrato:') !!}
            <div class="form-control">
                {{ $medicaoBoletim->contrato_id . ' - '. $medicaoBoletim->contrato->fornecedor->nome }}
            </div>
        </div>
    </div>
    <div class="row">
        @include('medicao_boletims.grid-medicoes')
    </div>
@stop