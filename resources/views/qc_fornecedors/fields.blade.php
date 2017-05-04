<!-- Quadro De Concorrencia Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quadro_de_concorrencia_id', 'Quadro De Concorrencia Id:') !!}
    {!! Form::number('quadro_de_concorrencia_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Fornecedor Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fornecedor_id', 'Fornecedor Id:') !!}
    {!! Form::number('fornecedor_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Rodada Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rodada', 'Rodada:') !!}
    {!! Form::number('rodada', null, ['class' => 'form-control']) !!}
</div>

<!-- Porcentagem Material Field -->
<div class="form-group col-sm-6">
    {!! Form::label('porcentagem_material', 'Porcentagem Material:') !!}
    {!! Form::number('porcentagem_material', null, ['class' => 'form-control']) !!}
</div>

<!-- Porcentagem Servico Field -->
<div class="form-group col-sm-6">
    {!! Form::label('porcentagem_servico', 'Porcentagem Servico:') !!}
    {!! Form::number('porcentagem_servico', null, ['class' => 'form-control']) !!}
</div>

<!-- Porcentagem Faturamento Direto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('porcentagem_faturamento_direto', 'Porcentagem Faturamento Direto:') !!}
    {!! Form::number('porcentagem_faturamento_direto', null, ['class' => 'form-control']) !!}
</div>

<!-- Desistencia Motivo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('desistencia_motivo_id', 'Desistencia Motivo Id:') !!}
    {!! Form::number('desistencia_motivo_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('qcFornecedors.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
