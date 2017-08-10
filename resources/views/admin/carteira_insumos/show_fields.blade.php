<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $carteiraInsumo->id !!}</p>
</div>

<!-- Carteira Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('carteira_id', 'Carteira Id:') !!}
    <p class="form-control">{!! $carteiraInsumo->carteira_id !!}</p>
</div>

<!-- Insumo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('insumo_id', 'Insumo Id:') !!}
    <p class="form-control">{!! $carteiraInsumo->insumo_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $carteiraInsumo->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $carteiraInsumo->updated_at !!}</p>
</div>

