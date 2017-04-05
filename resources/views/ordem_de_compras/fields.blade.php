<!-- Oc Status Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('oc_status_id', 'Oc Status Id:') !!}
    {!! Form::number('oc_status_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra Id:') !!}
    {!! Form::number('obra_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('ordemDeCompras.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
