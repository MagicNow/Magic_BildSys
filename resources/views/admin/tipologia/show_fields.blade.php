<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $estrutura->nome !!}</p>
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
