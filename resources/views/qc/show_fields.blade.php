<div class="form-group col-sm-6">
    {!! Form::label('tipologia_id', 'Tipologia') !!}
    <p class="form-control">{!! $qc->tipologia ? $qc->tipologia->nome : null !!}</p>
</div>

<!-- Carteira Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carteira_id', 'Carteira') !!}
    <p class="form-control">{!! $qc->carteira ? $qc->carteira->nome : NULL !!}</p>
</div>

<!-- Pbta Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra') !!}
    <p class="form-control">{!! $qc->obra ? $qc->obra->nome : 'Sem obra vinculada' !!}</p>
</div>

<!-- Pbta Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carteira_comprada', 'Carteira comprada?') !!}
    <p class="form-control">
        {{ $qc->carteira_comprada ? 'Sim' : 'Não' }}
    </p>
</div>

<div class="col-sm-12">
    <div class="row">
        <!-- Valor Pré Orçamento Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento') !!}
            <p class="form-control">{!! float_to_money($qc->valor_pre_orcamento) !!}</p>
        </div>

        <!-- Valor Orçamento Inicial Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial ') !!}
            <p class="form-control">{!! float_to_money($qc->valor_orcamento_inicial) !!}</p>
        </div>

        <!-- Valor da Gerencial Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('valor_gerencial', 'Valor da Gerencial') !!}
            <p class="form-control">{!! float_to_money($qc->valor_gerencial) !!}</p>
        </div>
    </div>
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
    {!! Form::label('descricao', 'Descrição do serviço') !!}
    <div class="panel panel-default">
        <div class="panel-body">
            {!! $qc->descricao !!}
        </div>
    </div>
</div>

@if($qc->canClose())
    <div class="form-group col-sm-3">
        {!! Form::label('valor_fechamento', 'Valor do Fechamento') !!}
        {!!
            Form::text(
                'valor_fechamento',
                $qc->valor_fechamento ? float_to_money($qc->valor_fechamento , '') : null,
                [ 'class' => 'form-control money' ]
            )
        !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('numero_contrato_mega', 'Número do Contrato (MEGA)') !!}
        {!! Form::number('numero_contrato_mega', null, [ 'class' => 'form-control' ]) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('fornecedor_id', 'Fornecedor') !!}
        {!!
            Form::select(
                'fornecedor_id',
                [],
                null,
                [
                    'class' => 'form-control',
                    'id' => 'fornecedor_id'
                ]
            )
        !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('comprador_id', 'Comprador') !!}
        {!!
            Form::select(
                'comprador_id',
                $compradores,
                null,
                [
                    'class' => 'form-control select2',
                    'id' => 'comprador_id',
                    'placeholder' => 'Selecione o comprador...'
                ]
            )
        !!}
    </div>
    <div class="col-sm-12">
        <div class="box box-solid box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-paperclip"></i> ANEXAR Q.C FECHADO
                </h3>
            </div>
            <div class="box-body">
                <div class="form-group row qc-anexos-campos">
                    <div class="col-sm-6">
                       {!! Form::label('', 'Descrição') !!}
                       {!! Form::hidden('anexo_descricao[]', 'Q.C. Fechado') !!}
                       <p class="form-control">
                            Q.C. Fechado
                       </p>
                    </div>
                   {!! Form::hidden('anexo_tipo[]', 'Quadro de concorrência') !!}
                    <div class="col-sm-6">
                       {!! Form::label('', 'Arquivo') !!}
                       {!! Form::file('anexo_arquivo[]', ['id' => 'qc-fechado', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="form-group col-sm-3">
        {!! Form::label('valor_fechamento', 'Valor do Fechamento') !!}
        <p class="form-control">
            {{
                $qc->valor_fechamento
                    ? float_to_money($qc->valor_fechamento)
                    : 'Dados não apresentados'
            }}
        </p>
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('numero_contrato_mega', 'Número do Contrato (MEGA)') !!}
        <p class="form-control">
            {{
                $qc->numero_contrato_mega
                    ? $qc->numero_contrato_mega
                    : 'Dados não apresentados'
            }}
        </p>
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('comprador_id', 'Fornecedor') !!}
        <p class="form-control">
            {{
                $qc->fornecedor
                    ? $qc->fornecedor->nome
                    : 'Dados não apresentados'
            }}
        </p>
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('comprador_id', 'Comprador') !!}
        <p class="form-control">
            {{
                $qc->comprador
                    ? $qc->comprador->name
                    : 'Dados não apresentados'
            }}
        </p>
    </div>
@endif

<div class="col-sm-12">
    <div class="box box-muted">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-paperclip"></i> ANEXOS
            </h3>
        </div>
        <div class="box-body">
            @if(count($attachments))
                @foreach($attachments as $title => $anexos)
                    <div class="h4">
                        {{ $title }}
                    </div>
                    @foreach($anexos as $anexo)
                        <ul>
                            <li>
                                {{ $anexo->descricao ? $anexo->descricao : 'Anexo ' . $anexo->id }}
                                <a target="_blank" class="label label-primary" href="{{ $anexo->link }}">
                                    <i class="fa fa-paperclip"></i> Link
                                </a>
                                &nbsp;
                                <a download class="label label-warning" href="{{ $anexo->link }}">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </li>
                        </ul>
                    @endforeach
                @endforeach
            @else
                O Q.C. #{{ $qc->id }} não contém nenhum anexo
            @endif
        </div>
    </div>
</div>
