<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $tarefaPadrao->nome !!}</p>
</div>

<!-- Resumo Field -->
<div class="form-group col-md-3">
    {!! Form::label('resumo', 'Resumo:') !!}
    <p class="form-control">{!! $tarefaPadrao->resumo !!}</p>
</div>

<!-- Crítica Field -->
<div class="form-group col-md-3">
    {!! Form::label('nome', 'Crítica:') !!}
    <p class="form-control">{!! $tarefaPadrao->critica !!}</p>
</div>

<!-- Torre Field -->
<div class="form-group col-md-6">
    {!! Form::label('torre', 'Torre:') !!}
    <p class="form-control">{!! $tarefaPadrao->torre !!}</p>
</div>

<!-- Pavimento Field -->
<div class="form-group col-md-6">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    <p class="form-control">{!! $tarefaPadrao->pavimento !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Cadastrada em:') !!}
    <p class="form-control">{!! $tarefaPadrao->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterada em:') !!}
    <p class="form-control">{!! $tarefaPadrao->updated_at->format('d/m/Y H:i') !!}</p>
</div>
