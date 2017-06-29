<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->nome !!}</p>
</div>

<!-- Tipo Field -->
<div class="form-group col-md-6">
    {!! Form::label('tipo', 'Tipo:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->tipo !!}</p>
</div>

<!-- Apenas Cartela Field -->
<div class="form-group col-md-6">
    {!! Form::label('apenas_cartela', 'Apenas Cartela:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->apenas_cartela !!}</p>
</div>

<!-- Apenas Unidade Field -->
<div class="form-group col-md-6">
    {!! Form::label('apenas_unidade', 'Apenas Unidade:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->apenas_unidade !!}</p>
</div>

