<!-- Valor Sugerido Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_sugerido', 'Valor Sugerido:') !!}
    {!! Form::text('valor_sugerido', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('lpu.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>