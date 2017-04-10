<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $workflowAlcada->id !!}</p>
</div>

<!-- Workflow Tipo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('workflow_tipo_id', 'Workflow Tipo Id:') !!}
    <p class="form-control">{!! $workflowAlcada->workflow_tipo_id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $workflowAlcada->nome !!}</p>
</div>

<!-- Ordem Field -->
<div class="form-group col-md-6">
    {!! Form::label('ordem', 'Ordem:') !!}
    <p class="form-control">{!! $workflowAlcada->ordem !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $workflowAlcada->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $workflowAlcada->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group col-md-6">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p class="form-control">{!! $workflowAlcada->deleted_at !!}</p>
</div>

