<!-- Tipos de Levantamento Field -->
<div class="form-group col-md-6">
    {!! Form::label('levantamento_tipos_id', 'Tipos de Levantamento:') !!}
    <p class="form-control">{!! $mascaraInsumo->levantamento_tipos_id !!}</p>
</div>

<!-- Apropriação Field -->
<div class="form-group col-md-6">
    {!! Form::label('apropriacao', 'Apropriação:') !!}
    <p class="form-control">{!! $mascaraInsumo->apropriacao !!}</p>
</div>

<!-- Descrição Apropriação Field -->
<div class="form-group col-md-6">
    {!! Form::label('descricao_apropriacao', 'Descrição Apropriação:') !!}
    <p class="form-control">{!! $mascaraInsumo->descricao_apropriacao !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('unidade_sigla', 'Unidade Medida:') !!}
    <p class="form-control">{!! $mascaraInsumo->unidade_sigla !!}</p>
</div>