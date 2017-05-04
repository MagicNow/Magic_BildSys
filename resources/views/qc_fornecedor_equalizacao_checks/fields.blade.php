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

<!-- Checkable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkable_type', 'Checkable Type:') !!}
    {!! Form::text('checkable_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Checkable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkable_id', 'Checkable Id:') !!}
    {!! Form::number('checkable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('qcFornecedorEqualizacaoChecks.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
