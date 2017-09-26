{!! Form::hidden('contrato_id', $contrato->id) !!}
<!-- Numero Documento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero_documento', 'Numero Documento:') !!}
    {!! Form::number('numero_documento', null, ['class' => 'form-control','required']) !!}
</div>

@if(count($fornecedores)>1)
    <!-- Fornecedor Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
        {!! Form::select('fornecedor_id', [''=>'Selecione...'] + $fornecedores, null, ['class' => 'form-control select2']) !!}
    </div>
@else
    {!! Form::hidden('fornecedor_id',  $contrato->fornecedor_id) !!}
@endif

<!-- Data Emissao Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_emissao', 'Data Emissão:') !!}
    {!! Form::date('data_emissao', (!isset($pagamento)?null:$pagamento->data_emissao->format('Y-m-d')), ['class' => 'form-control','required']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::text('valor', null, ['class' => 'form-control money']) !!}

</div>

<!-- Pagamento Condicao Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pagamento_condicao_id', 'Condição de Pagamento:') !!}
    {!! Form::select('pagamento_condicao_id',[''=>'Selecione...'] + $pagamentoCondicoes ,null, [
        'class' => 'form-control select2',
        'required' => 'required'
        ]) !!}
</div>

<!-- Documento Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documento_tipo_id', 'Tipo de Documento Fiscal:') !!}
    {!! Form::select('documento_tipo_id', [''=>'Selecione...'] + $documentoTipos ,null, ['class' => 'form-control select2', 'required']) !!}
</div>

<!-- Notas Fiscal Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notas_fiscal_id', 'Notas Fiscal:') !!}
    {!! Form::number('notas_fiscal_id', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    <h4>
        Parcelas
        <button type="button" class="btn btn-flat btn-primary pull-right btn-xs">
            <i class="fa fa-plus"></i>
            Adicionar parcela
        </button>
    </h4>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-3">
                    <label>Valor</label>
                    {!! Form::text('valor', null, ['class' => 'form-control money text-right','placeholder'=>'Valor da parcela']) !!}
                </div>

                <div class="col-xs-3">
                    <label>Nº Documento</label>
                    {!! Form::text('numero_documento', null, ['class' => 'form-control money text-right','placeholder'=>'Número do Documento']) !!}
                </div>

                <div class="col-xs-2">
                    <label>Data Vencimento</label>
                    {!! Form::date('data_vencimento', null, ['class' => 'form-control','placeholder'=>'Vencimento']) !!}
                </div>

                <div class="col-xs-1">
                    <label>% Desconto</label>
                    {!! Form::text('percentual_desconto', null, ['class' => 'form-control money text-right','placeholder'=>'% Desconto']) !!}
                </div>
                <div class="col-xs-2">
                    <label>Valor Desconto</label>
                    {!! Form::text('valor_desconto', null, ['class' => 'form-control money text-right','placeholder'=>'Valor desconto']) !!}
                </div>
                <div class="col-xs-1 text-right">
                    <button type="button" class="btn btn-danger btn-xs btn-flat">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="row" style="margin-top: 10px">
                <div class="col-xs-2">
                    <label>% Juro Mora</label>
                    {!! Form::text('percentual_juro_mora', null, ['class' => 'form-control money text-right', 'placeholder'=>'% Juro Mora']) !!}
                </div>
                <div class="col-xs-3">
                    <label>Valor Juro Mora</label>
                    {!! Form::text('valor_juro_mora', null, ['class' => 'form-control money text-right', 'placeholder'=>'Valor Juro Mora']) !!}
                </div>
                <div class="col-xs-2">
                    <label>% Multa</label>
                    {!! Form::text('percentual_multa', null, ['class' => 'form-control money text-right', 'placeholder'=>'% Multa']) !!}
                </div>
                <div class="col-xs-3">
                    <label>Valor Multa</label>
                    {!! Form::text('valor_multa', null, ['class' => 'form-control money text-right', 'placeholder'=>'Valor Multa']) !!}
                </div>
                <div class="col-xs-2">
                    <label>Data Base Multa</label>
                    {!! Form::date('data_base_multa', null, ['class' => 'form-control','placeholder'=>'Data Base Multa']) !!}
                </div>
            </div>
        </li>
    </ul>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-lg btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('pagamentos.index') !!}" class="btn btn-lg btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
