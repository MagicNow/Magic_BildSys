<div class="form-group col-md-4">
    {!! Form::label('obra', 'Obra:') !!}
    <div class="form-control">
        {{ $medicaoBoletim->obra->nome }}
    </div>
</div>

<!-- Contrato Id Field -->
<div class="form-group col-md-4">
    {!! Form::label('contrato_id', 'Contrato:') !!}
    <div class="form-control">
        {{ $medicaoBoletim->contrato_id . ' - '. $medicaoBoletim->contrato->fornecedor->nome }}
    </div>
</div>

<!-- Medicao Boletim Status Id Field -->
<div class="form-group col-md-4">
    {!! Form::label('medicao_boletim_status_id', 'Situação:') !!}
    <p class="form-control">{!! $medicaoBoletim->medicaoBoletimStatus->nome !!}</p>
</div>

<!-- Obs Field -->
<div class="form-group col-md-12">
    {!! Form::label('obs', 'Observações:') !!}
    <div class="well well-sm">{!! nl2br($medicaoBoletim->obs) !!}</div>
</div>


@include('medicao_boletims.grid-medicoes')
