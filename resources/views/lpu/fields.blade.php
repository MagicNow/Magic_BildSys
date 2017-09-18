
<!-- Valor Sugerido Anterior Field -->
<div class="form-group col-sm-3">
    {!! Form::label('valor_sugerido', 'Valor Atual:') !!}    
	{!! Form::text('valor_sugerido', null, ['class' => 'form-control money', 'disabled']) !!}	
</div>

<!-- Valor Sugerido Field -->
<div class="form-group col-sm-3">
    {!! Form::label('valor_sugerido', 'Sugerir Valor:') !!}
    {!! Form::text('valor_sugerido', null, ['class' => 'form-control money', 'required']) !!}
</div>

<!-- Observação Field -->
<div class="form-group col-sm-6">
    {!! Form::label('observacao', 'Observação da Alteração:') !!}
    {!! Form::textarea('observacao', null, ['class' => 'form-control', 'id'=>'obs', 'rows'=>4]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('lpu.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>