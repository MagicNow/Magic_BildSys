<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qc Status Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qc_status_id', 'Qc Status Id:') !!}
    {!! Form::number('qc_status_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Rodada Atual Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rodada_atual', 'Rodada Atual:') !!}
    {!! Form::number('rodada_atual', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('quadro-de-concorrencias.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
