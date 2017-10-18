<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control']) !!}
</div>

<!-- Tarefa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tarefa', 'Nome Tarefa:') !!}
    {!! Form::text('tarefa', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data', 'Data início:') !!}
    {!! Form::date('data',isset($planejamento)? $planejamento->data->format('Y-m-d') : null, ['class' => 'form-control']) !!}
</div>

<!-- Prazo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('prazo', 'Prazo:') !!}
    {!! Form::number('prazo', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Fim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_fim', 'Data Fim:') !!}
    {!! Form::date('data_fim',isset($planejamento)? $planejamento->data_fim->format('Y-m-d') : null, ['class' => 'form-control']) !!}
</div>

<!-- Resumo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resumo', 'Resumo:') !!}
    {!! Form::text('resumo', null, ['class' => 'form-control']) !!}
</div>
@if(request()->get('carteira_avulsa'))
    {!! Form::hidden('carteira_avulsa',1) !!}
    <div class="form-group col-sm-12">
        {!! Form::label('planejamentoQcAvulsoCarteira', 'Carteiras de Q.C. Avulso:') !!}
        {!! Form::select('planejamentoQcAvulsoCarteira[]', $qcAvulsoCarteiras , (!isset($planejamento)? null : $planejamentoCarteirasIds), ['class' => 'form-control select2', 'multiple'=>"multiple"]) !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! request()->get('carteira_avulsa')? route('admin.planejamentos.atividade-carteiras') : route('admin.planejamentos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>