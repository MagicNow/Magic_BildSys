<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->nome !!}</p>
</div>

<!-- Unidades Sigla Field -->
<div class="form-group col-md-6">
    {!! Form::label('unidade_sigla', 'Sigla:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->unidade_sigla !!}</p>
</div>

<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo', 'Código:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->codigo !!}</p>
</div>

<!-- Insumo Grupo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('insumo_grupo_id', 'Grupo:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->insumoGrupo->nome !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->created_at->format('d/m/Y')  !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $solicitacaoInsumo->updated_at->format('d/m/Y')  !!}</p>
</div>