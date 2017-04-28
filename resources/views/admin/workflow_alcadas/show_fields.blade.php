<!-- Workflow Tipo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('workflow_tipo_id', 'Workflow Tipo Id:') !!}
    <p class="form-control">{!! $workflowAlcada->workflowTipo->nome !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $workflowAlcada->nome !!}</p>
</div>

<!-- Ordem Field -->
<div class="form-group col-md-4">
    {!! Form::label('ordem', 'Ordem:') !!}
    <p class="form-control">{!! $workflowAlcada->ordem !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-4">
    {!! Form::label('created_at', 'Criada em:') !!}
    <p class="form-control">{!! $workflowAlcada->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-4">
    {!! Form::label('updated_at', 'Atualizada em:') !!}
    <p class="form-control">{!! $workflowAlcada->updated_at->format('d/m/Y') !!}</p>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('workflowUsuarios', 'Usuários nesta alçada:') !!}
    {!! Form::select('workflowUsuarios[]', $relacionados ,(!isset($workflowAlcada)? null: $workflowUsuarios_ids), ['class' => 'form-control', 'id'=>"workflowUsuarios", 'multiple'=>"multiple"]) !!}
</div>
