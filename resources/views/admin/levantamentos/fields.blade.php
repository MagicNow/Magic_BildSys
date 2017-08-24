<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control']) !!}
</div>

<!-- Torre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('torre', 'Torre:') !!}
    {!! Form::text('torre', null, ['class' => 'form-control']) !!}
</div>


<!-- Pavimento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    {!! Form::text('pavimento', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.levantamentos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>