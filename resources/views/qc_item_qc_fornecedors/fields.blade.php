<!-- Qc Item Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qc_item_id', 'Qc Item Id:') !!}
    {!! Form::number('qc_item_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qc Fornecedor Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qc_fornecedor_id', 'Qc Fornecedor Id:') !!}
    {!! Form::number('qc_fornecedor_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qtd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd', 'Qtd:') !!}
    {!! Form::number('qtd', null, ['class' => 'form-control']) !!}
</div>

<!-- Valor Unitario Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_unitario', 'Valor Unitario:') !!}
    {!! Form::number('valor_unitario', null, ['class' => 'form-control']) !!}
</div>

<!-- Valor Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_total', 'Valor Total:') !!}
    {!! Form::number('valor_total', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Decisao Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_decisao', 'Data Decisao:') !!}
    {!! Form::date('data_decisao', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('qcItemQcFornecedors.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
