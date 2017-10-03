<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $grupo->id !!}</p>
</div>

<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo', 'Codigo:') !!}
    <p class="form-control">{!! $grupo->codigo !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $grupo->nome !!}</p>
</div>

<!-- Grupo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('grupo_id', 'Grupo Id:') !!}
    <p class="form-control">{!! $grupo->grupo_id !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group col-md-6">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p class="form-control">{!! $grupo->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $grupo->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $grupo->updated_at !!}</p>
</div>

