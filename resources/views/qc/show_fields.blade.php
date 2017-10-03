<!-- ID Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'ID:') !!}
    <p class="form-control">{!! $qc->id !!}</p>
</div>

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipologia_id', 'Tipologia:') !!}
    <p class="form-control">{!! $qc->tipologia->nome !!}</p>
</div>

<!-- Carteira Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carteira_id', 'Carteira:') !!}
    <p class="form-control">{!! $qc->carteira->nome !!}</p>
</div>

<!-- Pbta Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    <p class="form-control">{!! $qc->obra->nome !!}</p>
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
    {!! Form::label('descricao', 'Descrição do serviço:') !!}
    <p class="form-control">{!! $qc->descricao !!}</p>
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

<div class="form-group col-sm-6">
    <div class="checkbox">
        <label>
            {!! Form::checkbox('carteira_comprada', '1', isset($qc) && $qc->carteira_comprada == 1 ? true : false, ['class' => 'form-control', 'id' => 'carteira_comprada', 'readonly' => 'true', 'disabled' => 'disabled']) !!}
            Carteira Comprada
        </label>
    </div>
</div>
@if (isset($attachments) && !empty($attachments))
    <fieldset class="col-sm-12 table-responsive">
        <legend>Anexos</legend>
        @foreach ($attachments as $key => $attachment)
            <h5 style="color: #000; font-size: 16px;">{{ $key }}</h5>
            <table class="table">
                <thead>
                    <tr>
                        <td class="col-sm-10 text-left"><strong>Descrição</strong></td>
                        <td class="col-sm-1"><strong>Ações</strong></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attachment as $item)
                        <tr>
                            <td class="text-left">{{ $item->descricao }}</td>
                            <td><a href="{{ url(str_replace('public', 'storage', $item->arquivo)) }}" target="_blank" title="Download" class="btn"><i class="fa fa-paperclip" aria-hidden="true"></i></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </fieldset>
@endif