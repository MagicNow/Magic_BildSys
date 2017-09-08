
<!-- Valor Sugerido Anterior Field -->
<div class="form-group col-sm-2">
    {!! Form::label('valor_sugerido_anterior', 'Valor Sugerido Anterior:') !!}    
	<p class="form-control input-lg text-center">
		<small class="pull-left">R$</small>{{ float_to_money($lpu->valor_sugerido_anterior, '') }}	
	</p>	
</div>

<!-- Valor Sugerido Field -->
<div class="form-group col-sm-3">
    {!! Form::label('valor_sugerido_atual', 'Sugerir Valor:') !!}
    {!! Form::text('valor_sugerido_atual', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('lpu.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>