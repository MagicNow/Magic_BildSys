<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $pagamentoCondicao->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $pagamentoCondicao->nome !!}</p>
</div>

<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo', 'Codigo:') !!}
    <p class="form-control">{!! $pagamentoCondicao->codigo !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $pagamentoCondicao->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $pagamentoCondicao->updated_at !!}</p>
</div>

