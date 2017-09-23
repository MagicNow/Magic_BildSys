<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $requisicao->id !!}</p>
</div>

<!-- Obra Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('obra_id', 'Obra Id:') !!}
    <p class="form-control">{!! $requisicao->obra_id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('user_id', 'User Id:') !!}
    <p class="form-control">{!! $requisicao->user_id !!}</p>
</div>

<!-- Status Field -->
<div class="form-group col-md-6">
    {!! Form::label('status', 'Status:') !!}
    <p class="form-control">{!! $requisicao->status !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $requisicao->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $requisicao->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group col-md-6">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p class="form-control">{!! $requisicao->deleted_at !!}</p>
</div>

