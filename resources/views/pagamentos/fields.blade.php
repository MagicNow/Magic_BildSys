{!! Form::hidden('contrato_id', $contrato->id) !!}
<!-- Numero Documento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero_documento', 'Numero Documento:') !!}
    {!! Form::number('numero_documento', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Fornecedor Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
    {!! Form::number('fornecedor_id', null, ['class' => 'form-control']) !!}
</div>

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

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-lg btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('pagamentos.index') !!}" class="btn btn-lg btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
