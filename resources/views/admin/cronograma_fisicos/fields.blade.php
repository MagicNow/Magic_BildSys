<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control']) !!}
</div>


<!-- Resumo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resumo', 'Resumo:') !!}
    {!! Form::text('resumo', null, ['class' => 'form-control']) !!}
</div>

<!-- Tarefa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tarefa', 'Nome Tarefa:') !!}
    {!! Form::text('tarefa', null, ['class' => 'form-control']) !!}
</div>

<!-- Custo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('custo', 'Custo:') !!}
    {!! Form::number('custo', null, ['class' => 'form-control']) !!}
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

<!-- Concluida Field -->
<div class="form-group col-sm-6">
    {!! Form::label('concluida', 'Concluida(%):') !!}
    {!! Form::text('concluida', null, ['class' => 'form-control']) !!}
</div>

<!-- Critica Field -->
<div class="form-group col-sm-6">
    {!! Form::label('critica', 'Critica:') !!}
    {!! Form::text('critica', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_inicio', 'Data início:') !!}
    {!! Form::date('data_inicio', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Fim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_termino', 'Data Fim:') !!}
    {!! Form::date('data_termino', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.cronograma_fisicos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>