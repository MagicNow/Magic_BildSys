<!-- Obra Id Field -->
<div class="form-group col-md-12">
    {!! Form::label('obra_id', 'Obra:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->obra->nome !!}</p>
</div>

<!-- Origem Field -->
<div class="form-group col-md-12">
    {!! Form::label('origem', 'Origem:') !!}
    <p class="form-control"><a href="{!! $retroalimentacaoObra->origem !!}">{!! $retroalimentacaoObra->origem !!}</a></p>
</div>

<!-- Situacao Atual Field -->
<div class="form-group col-md-6">
    {!! Form::label('situacao_atual', 'Situação Atual:') !!}
    {!! Form::textarea('situacao_proposta', $retroalimentacaoObra->situacao_atual, ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
</div>

<!-- Situacao Proposta Field -->
<div class="form-group col-md-6">
    {!! Form::label('situacao_proposta', 'Situação Proposta:') !!}
    {!! Form::textarea('situacao_proposta', $retroalimentacaoObra->situacao_proposta, ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
</div>

<!-- Ação Field -->
<div class="form-group col-md-6">
    {!! Form::label('acao', 'Ação:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->acao !!}</p>
</div>

<!-- Resultado obtido Field -->
<div class="form-group col-md-6">
    {!! Form::label('status', 'Resultado obtido:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->resultado_obtido !!}</p>
</div>

<!-- Status Field -->
<div class="form-group col-md-6">
    {!! Form::label('status', 'Status:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->status !!}</p>
</div>

<!-- Categoria Field -->
<div class="form-group col-md-6">
    {!! Form::label('categoria', 'Categoria:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->categoria !!}</p>
</div>

<div class="form-group col-sm-6">
{!! Form::label('data_prevista', 'Data prevista:') !!}
{!! Form::date('data_prevista', isset($retroalimentacaoObra) ? $retroalimentacaoObra->data_prevista ? $retroalimentacaoObra->data_prevista->format('d/m/Y') : null : null, ['class' => 'form-control', 'readonly' => true]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('data_conclusao', 'Data conclusão:') !!}
    {!! Form::date('data_conclusao', isset($retroalimentacaoObra) ? $retroalimentacaoObra->data_conclusao ? $retroalimentacaoObra->data_conclusao->format('d/m/Y') : null : null, ['class' => 'form-control', 'readonly' => true]) !!}
</div>
<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Data de inclusão:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $retroalimentacaoObra->updated_at->format('d/m/Y') !!}</p>
</div>

