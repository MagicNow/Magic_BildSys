<!-- Obra Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->obra->nome !!}</p>
</div>

<!-- Origem Field -->
<div class="form-group col-md-6">
    {!! Form::label('origem', 'Origem:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->origem !!}</p>
</div>

<!-- Categoria Field -->
<div class="form-group col-md-6">
    {!! Form::label('categoria', 'Categoria:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->categoria !!}</p>
</div>

<!-- Data Inclusao Field -->
<div class="form-group col-md-6">
    {!! Form::label('data_inclusao', 'Data Inclusão:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->data_inclusao ? $retroalimentacaoObra->data_inclusao->format('d/m/Y') : null !!}</p>
</div>

<!-- Situacao Atual Field -->
<div class="form-group col-md-6">
    {!! Form::label('situacao_atual', 'Situacao Atual:') !!}
    {!! Form::textarea('situacao_proposta', $retroalimentacaoObra->situacao_atual, ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
</div>

<!-- Situacao Proposta Field -->
<div class="form-group col-md-6">
    {!! Form::label('situacao_proposta', 'Situacao Proposta:') !!}
    {!! Form::textarea('situacao_proposta', $retroalimentacaoObra->situacao_proposta, ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->updated_at->format('d/m/Y') !!}</p>
</div>

