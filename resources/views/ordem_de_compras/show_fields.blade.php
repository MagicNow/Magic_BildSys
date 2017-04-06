<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $ordemDeCompra->id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $ordemDeCompra->created_at !!}</p>
</div>

<!-- Oc Status Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('oc_status_id', 'Oc Status Id:') !!}
    <p class="form-control">{!! $ordemDeCompra->oc_status_id !!}</p>
</div>

<!-- Obra Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('obra_id', 'Obra Id:') !!}
    <p class="form-control">{!! $ordemDeCompra->obra_id !!}</p>
</div>

<!-- Aprovado Field -->
<div class="form-group col-md-6">
    {!! Form::label('aprovado', 'Aprovado:') !!}
    <p class="form-control">{!! $ordemDeCompra->aprovado !!}</p>
</div>

