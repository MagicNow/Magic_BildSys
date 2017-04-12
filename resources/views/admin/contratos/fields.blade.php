<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+ \App\Models\Obra::pluck('nome','id')->toArray() , null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data', 'Data:') !!}
    {!! Form::date('data', null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::text('valor', null, ['class' => 'form-control money','required'=>'required']) !!}
</div>

<!-- Arquivo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arquivo', 'Arquivo:') !!}
    @if($contratos)
        @if($contratos->arquivo)
            <a href="{{$contratos->arquivo}}" download>Baixar arquivo</a>
        @endif
    @endif
    {!! Form::file('arquivo', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.contratos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
