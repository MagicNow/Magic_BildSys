<!-- Contrato Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contrato_id', 'Contrato Id:') !!}
    {!! Form::number('contrato_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Insumo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('insumo_id', 'Insumo Id:') !!}
    {!! Form::number('insumo_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qtd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd', 'Qtd:') !!}
    {!! Form::number('qtd', null, ['class' => 'form-control']) !!}
</div>

<!-- Valor Unitario Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_unitario', 'Valor Unitario:') !!}
    {!! Form::number('valor_unitario', null, ['class' => 'form-control']) !!}
</div>

<!-- Valor Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_total', 'Valor Total:') !!}
    {!! Form::number('valor_total', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.contratoInsumos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
