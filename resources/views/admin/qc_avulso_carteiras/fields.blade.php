<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('users', 'Compradores para esta carteira:') !!}
    {!! Form::select('users[]', $usuarios , (!isset($carteira )? null : $carteiraUsers), ['class' => 'form-control select2', 'id'=>"carteiraUsers", 'multiple'=>"multiple"]) !!}
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('sla_start', 'SLA Start:') !!}
    {!! Form::number('sla_start', null, ['class' => 'form-control', 'required']) !!}
</div>
<!-- Dias Prazo Minimo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('sla_negociacao', 'SLA Negociação:') !!}
    {!! Form::number('sla_negociacao', null, ['class' => 'form-control', 'required']) !!}
</div>
<!-- Dias Prazo Minimo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('sla_mobilizacao', 'SLA Mobilização:') !!}
    {!! Form::number('sla_mobilizacao', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.qc_avulso_carteiras.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>