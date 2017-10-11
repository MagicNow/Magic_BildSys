<div class="form-group col-sm-6">
    {!! Form::label('tipologia_id', 'Tipologia:') !!}
    <p class="form-control">{!! $qc->tipologia->nome !!}</p>
</div>

<!-- Carteira Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carteira_id', 'Carteira:') !!}
    <p class="form-control">{!! isset($qc->carteira) ? $qc->carteira->nome : NULL !!}</p>
</div>

<!-- Pbta Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    <p class="form-control">{!! isset($qc->obra) ? $qc->obra->nome : NULL !!}</p>
</div>
<!-- Valor Pré Orçamento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento:') !!}
    <p class="form-control">{!! float_to_money($qc->valor_pre_orcamento) !!}</p>
</div>

<!-- Valor Orçamento Inicial Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial :') !!}
    <p class="form-control">{!! float_to_money($qc->valor_orcamento_inicial) !!}</p>
</div>

<!-- Valor da Gerencial Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_gerencial', 'Valor da Gerencial:') !!}
    <p class="form-control">{!! float_to_money($qc->valor_gerencial) !!}</p>
</div>


<!-- Descrição Field -->
<div class="form-group col-sm-12">
    {!! Form::label('descricao', 'Descrição do serviço:') !!}
    <div class="panel panel-default">
        <div class="panel-body">
            {!! $qc->descricao !!}
        </div>
    </div>
</div>


@if (!isset($screen) || $screen !== 'approve')
    <!-- Created At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('created_at', 'Criado em:') !!}
        <p class="form-control">{!! $qc->created_at->format('d/m/Y') !!}</p>
    </div>

    <!-- Updated At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('updated_at', 'Alterado em:') !!}
        <p class="form-control">{!! $qc->updated_at->format('d/m/Y') !!}</p>
    </div>
@endif

<div class="col-sm-12">
    <div class="box box-muted">
        <div class="box-header with-border">
            <i class="fa fa-paperclip"></i> ANEXOS
        </div>
        <div class="box-body">
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
        </div>
    </div>
</div>
