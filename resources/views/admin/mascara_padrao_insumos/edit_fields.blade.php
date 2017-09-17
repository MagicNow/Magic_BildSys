<!-- Máscara Padrão Field -->
<div class="form-group col-sm-3">
	{!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
	{!! Form::select('mascara_padrao_id', [''=>'-']+$mascaraPadrao, null, ['class' => 'form-control select2', 'id'=>'mascara_padrao_id', 'required'=>'required' , 'disabled' => true ]) !!}
</div>

<!-- Insumo Field -->
<div class="form-group col-sm-3">
	{!! Form::label('insumo_id', 'Insumos:') !!}
	{!! Form::select('insumo_id', [''=>'-']+$insumos, null, ['class' => 'form-control select2', 'id'=>'insumo_id', 'required'=>'required' , 'disabled' => true ]) !!}
</div>

<!-- Código Estruturado Field -->
<div class="form-group col-sm-3">
	{!! Form::label('codigo_insumo', 'Código Estruturado:') !!}
	{!! Form::text('codigo_insumo', null, ['class' => 'form-control' , 'readonly' => true]) !!}
</div>

<!-- Coeficiente Field -->
<div class="form-group col-sm-3">
	{!! Form::label('coeficiente', 'Coeficiente:') !!}
	{!! Form::text('coeficiente', null, ['class' => 'form-control money']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('admin.mascara_padrao_insumos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>