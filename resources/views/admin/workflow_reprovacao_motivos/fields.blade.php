<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Tipo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('workflow_tipo_id', 'Tipo:') !!}
    {!! Form::select('workflow_tipo_id',[''=>'Todos']+\App\Models\WorkflowTipo::pluck('nome','id')->toArray(), null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.workflowReprovacaoMotivos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
