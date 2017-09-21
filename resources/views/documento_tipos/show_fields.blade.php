<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $documentoTipo->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $documentoTipo->nome !!}</p>
</div>

<!-- Codigo Mega Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo_mega', 'Codigo Mega:') !!}
    <p class="form-control">{!! $documentoTipo->codigo_mega !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $documentoTipo->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $documentoTipo->updated_at !!}</p>
</div>

