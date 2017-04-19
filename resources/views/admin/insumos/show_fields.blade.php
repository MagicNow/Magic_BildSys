
<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $insumo->nome !!}</p>
</div>

<!-- Unidade Sigla Field -->
<div class="form-group col-md-6">
    {!! Form::label('unidade_sigla', 'Unidade:') !!}
    <p class="form-control">{!! $insumo->unidade_sigla. ($insumo->unidade ?  ' - ' . $insumo->unidade()->first()->descricao : '') !!}</p>
</div>

<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo', 'Codigo:') !!}
    <p class="form-control">{!! $insumo->codigo !!}</p>
</div>

<!-- Insumo Grupo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('insumo_grupo_id', 'Insumo Grupo:') !!}
    <p class="form-control">{!! $insumo->insumoGrupo->nome !!}</p>
</div>

