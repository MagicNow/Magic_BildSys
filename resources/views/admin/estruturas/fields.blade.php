<!-- Torre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('torre', 'Torre:') !!}
    {!! Form::text('torre', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Pavimento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    {!! Form::text('pavimento', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Trecho Field -->
<div class="form-group col-sm-6">
    {!! Form::label('trecho', 'Trecho:') !!}
    {!! Form::text('trecho', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.estruturas.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>