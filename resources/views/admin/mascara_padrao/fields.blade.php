<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- tipo de orçamentos Id Field -->
<div class="form-group col-sm-6">
	{!! Form::label('orcamento_tipo_id', 'Tipos de Orçamento:') !!}
	{!! Form::select('orcamento_tipo_id', $tipoOrcamentos, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.mascara_padrao.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
