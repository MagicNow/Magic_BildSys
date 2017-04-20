<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $templatePlanilha->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $templatePlanilha->nome !!}</p>
</div>

<!-- Modulo Field -->
<div class="form-group col-md-6">
    {!! Form::label('modulo', 'Modulo:') !!}
    <p class="form-control">{!! $templatePlanilha->modulo !!}</p>
</div>

<!-- Colunas Field -->
<div class="form-group col-md-6">
    {!! Form::label('colunas', 'Colunas:') !!}
    <p class="form-control">{!! $templatePlanilha->colunas !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $templatePlanilha->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $templatePlanilha->updated_at !!}</p>
</div>

