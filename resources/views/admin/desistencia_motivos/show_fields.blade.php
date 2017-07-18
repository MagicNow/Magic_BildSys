<!-- Nome Field -->
<div class="form-group col-md-12">
    {!! Form::label('nome', 'Motivo:') !!}
    <p class="form-control">{!! $desistenciaMotivo->nome !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $desistenciaMotivo->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterado em:') !!}
    <p class="form-control">{!! $desistenciaMotivo->updated_at->format('d/m/Y') !!}</p>
</div>

