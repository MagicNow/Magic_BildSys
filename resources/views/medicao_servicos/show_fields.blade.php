<div class="form-group col-md-6">
    {!! Form::label('id', 'Contrato - Fornecedor') !!}
    <p class="form-control">
        {!! $medicaoServico->contratoItemApropriacao->contratoItem->contrato_id !!} -
        {!! $medicaoServico->contratoItemApropriacao->contratoItem->contrato->fornecedor->nome !!}
    </p>
</div>

<!-- Insumo Field -->
<div class="form-group col-md-6">
    {!! Form::label('insumo', 'Insumo:') !!}
    <p class="form-control">
        {!! $medicaoServico->contratoItemApropriacao->codigo_insumo !!} -
        {!! $medicaoServico->contratoItemApropriacao->insumo->nome !!}
    </p>
</div>

<!-- Qtd Funcionarios Field -->
<div class="form-group col-md-4">
    {!! Form::label('qtd_funcionarios', 'Quantidade de Funcionários:') !!}
    <p class="form-control text-right">{!! $medicaoServico->qtd_funcionarios !!}</p>
</div>

<!-- Quantidade de Ajudantes Field -->
<div class="form-group col-md-4">
    {!! Form::label('qtd_ajudantes', 'Quantidade de Ajudantes:') !!}
    <p class="form-control text-right">{!! $medicaoServico->qtd_ajudantes !!}</p>
</div>

<!-- Quantidade de Outros Field -->
<div class="form-group col-md-4">
    {!! Form::label('qtd_outros', 'Quantidade de Outros:') !!}
    <p class="form-control text-right">{!! $medicaoServico->qtd_outros !!}</p>
</div>

<!-- Descontos Field -->
<div class="form-group col-md-6">
    {!! Form::label('descontos', 'Descontos:') !!}
    <p class="form-control text-right">{!! float_to_money($medicaoServico->descontos)  !!}</p>
</div>

<!-- Descricao Descontos Field -->
<div class="form-group col-md-6">
    {!! Form::label('descricao_descontos', 'Descrição dos Descontos:') !!}
    <p class="form-control">{!! $medicaoServico->descricao_descontos !!}</p>
</div>



<!-- Periodo Inicio Field -->
<div class="form-group col-md-4">
    {!! Form::label('periodo_inicio', 'Período Início:') !!}
    <p class="form-control text-center">{!! $medicaoServico->periodo_inicio->format('d/m/Y') !!}</p>
</div>

<!-- Período Termino Field -->
<div class="form-group col-md-4">
    {!! Form::label('periodo_termino', 'Período Término:') !!}
    <p class="form-control text-center">{!! $medicaoServico->periodo_termino->format('d/m/Y') !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-4">
    {!! Form::label('user_id', 'Usuário:') !!}
    <p class="form-control">{!! $medicaoServico->user->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $medicaoServico->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Última alteração:') !!}
    <p class="form-control">{!! $medicaoServico->updated_at->format('d/m/Y H:i') !!}</p>
</div>
