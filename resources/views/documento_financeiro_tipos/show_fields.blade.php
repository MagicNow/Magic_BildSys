<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $documentoFinanceiroTipo->nome !!}</p>
</div>

<!-- Codigo Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo_mega', 'Codigo:') !!}
    <p class="form-control">{!! $documentoFinanceiroTipo->codigo_mega !!}</p>
</div>

<!-- Retem Irrf Field -->
<div class="form-group col-md-6">
    {!! Form::label('retem_irrf', 'Retem Irrf:') !!}
    <p class="form-control">
        {!! $documentoFinanceiroTipo->retem_irrf?'<span class="text-success"><i class="fa fa-check"></i> SIM</span>':
        '<span class="text-danger"><i class="fa fa-times text-danger"></i> NÃO</span>' !!}
    </p>
</div>

<!-- Retem Impostos Field -->
<div class="form-group col-md-6">
    {!! Form::label('retem_impostos', 'Retem Impostos:') !!}
    <p class="form-control">
        {!! $documentoFinanceiroTipo->retem_impostos?'<span class="text-success"><i class="fa fa-check"></i> SIM</span>':
            '<span class="text-danger"><i class="fa fa-times text-danger"></i> NÃO</span>' !!}
    </p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $documentoFinanceiroTipo->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $documentoFinanceiroTipo->updated_at->format('d/m/Y H:i') !!}</p>
</div>

