<!-- Contrato Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contrato_id', 'Contrato Id:') !!}
    {!! Form::number('contrato_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra Id:') !!}
    {!! Form::number('obra_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Numero Documento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero_documento', 'Numero Documento:') !!}
    {!! Form::number('numero_documento', null, ['class' => 'form-control']) !!}
</div>

<!-- Fornecedor Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fornecedor_id', 'Fornecedor Id:') !!}
    {!! Form::number('fornecedor_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Emissao Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_emissao', 'Data Emissao:') !!}
    {!! Form::date('data_emissao', null, ['class' => 'form-control']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::number('valor', null, ['class' => 'form-control']) !!}
</div>

<!-- Pagamento Condicao Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pagamento_condicao_id', 'Pagamento Condicao Id:') !!}
    {!! Form::number('pagamento_condicao_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Documento Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documento_tipo_id', 'Documento Tipo Id:') !!}
    {!! Form::number('documento_tipo_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Notas Fiscal Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notas_fiscal_id', 'Notas Fiscal Id:') !!}
    {!! Form::number('notas_fiscal_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('pagamentos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
