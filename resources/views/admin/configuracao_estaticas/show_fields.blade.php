<!-- Chave Field -->
<div class="form-group col-md-12">
    {!! Form::label('chave', 'Nome:') !!}
    <p class="form-control">{!! $configuracaoEstatica->chave !!}</p>
</div>

<!-- Valor Field -->
<div class="form-group col-md-12">
    {!! Form::label('valor', 'Descrição:') !!}
    <textarea class="form-control">{!! $configuracaoEstatica->valor !!}</textarea>
</div>

