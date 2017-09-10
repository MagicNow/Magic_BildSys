<!-- Torre Field -->
<div class="form-group col-md-6">
    {!! Form::label('torre', 'Torre:') !!}
    <p class="form-control">{!! $estrutura->torre !!}</p>
</div>

<!-- Pavimento Field -->
<div class="form-group col-md-6">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    <p class="form-control">{!! $estrutura->pavimento !!}</p>
</div>

<!-- Trecho Field -->
<div class="form-group col-md-6">
    {!! Form::label('trecho', 'Trecho:') !!}
    <p class="form-control">{!! $estrutura->trecho !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Cadastrada em:') !!}
    <p class="form-control">{!! $estrutura->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterada em:') !!}
    <p class="form-control">{!! $estrutura->updated_at->format('d/m/Y H:i') !!}</p>
</div>
