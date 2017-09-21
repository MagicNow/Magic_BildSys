<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $pagamentoCondicao->id !!}</p>
</div>
<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo', 'Codigo:') !!}
    <p class="form-control">{!! $pagamentoCondicao->codigo !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-12">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $pagamentoCondicao->nome !!}</p>
</div>



<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $pagamentoCondicao->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $pagamentoCondicao->updated_at->format('d/m/Y H:i') !!}</p>
</div>

