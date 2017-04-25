<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-sm-3">
    {!! Form::label('dias_prazo_minimo', 'Dias Prazo Minimo:') !!}
    {!! Form::number('dias_prazo_minimo', null, ['class' => 'form-control']) !!}
</div>

<!-- Dias Prazo Maximo Field -->
<div class="form-group col-sm-3">
    {!! Form::label('dias_prazo_maximo', 'Dias Prazo Maximo:') !!}
    {!! Form::number('dias_prazo_maximo', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.lembreteTipos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
