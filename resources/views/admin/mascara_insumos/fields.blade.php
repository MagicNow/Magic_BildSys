<!-- Tipos de Levantamento -->
<div class="form-group col-sm-4">
	{!! Form::label('levantamento_tipos_id', 'Tipos de Levantamento:') !!}
	{!! Form::select('levantamento_tipos_id',[''=>'Escolha...']+$levantamentoTipos, null, ['class' => 'form-control']) !!}
</div>

<!-- Apropriação Field -->
<div class="form-group col-sm-4">
    {!! Form::label('apropriacao', 'Apropriação:') !!}
    {!! Form::text('apropriacao', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Descrição Apropriação Field -->
<div class="form-group col-sm-4">
    {!! Form::label('descricao_apropriacao', 'Descrição Apropriação:') !!}
    {!! Form::text('descricao_apropriacao', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Unidade Medida Field -->
<div class="form-group col-sm-4">
    {!! Form::label('unidade_sigla', 'Unidade Medida:') !!}
    {!! Form::text('unidade_sigla', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.mascara_insumos.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>