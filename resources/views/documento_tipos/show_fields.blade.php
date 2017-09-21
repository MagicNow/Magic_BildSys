<!-- Sigla Field -->
<div class="form-group col-md-6">
    {!! Form::label('sigla', 'Sigla:') !!}
    <p class="form-control">{!! $documentoTipo->sigla !!}</p>
</div>

<!-- Codigo Mega Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo_mega', 'Codigo Mega:') !!}
    <p class="form-control">{!! $documentoTipo->codigo_mega !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-12">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $documentoTipo->nome !!}</p>
</div>


<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $documentoTipo->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $documentoTipo->updated_at->format('d/m/Y H:i') !!}</p>
</div>

