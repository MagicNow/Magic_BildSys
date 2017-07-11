<!-- Mc Medicao Previsao Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mc_medicao_previsao_id', 'Mc Medicao Previsao Id:') !!}
    {!! Form::number('mc_medicao_previsao_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qtd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd', 'Qtd:') !!}
    {!! Form::number('qtd', null, ['class' => 'form-control']) !!}
</div>

<!-- Periodo Inicio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_inicio', 'Periodo Inicio:') !!}
    {!! Form::date('periodo_inicio', null, ['class' => 'form-control']) !!}
</div>

<!-- Periodo Termino Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_termino', 'Periodo Termino:') !!}
    {!! Form::date('periodo_termino', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('medicoes.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
