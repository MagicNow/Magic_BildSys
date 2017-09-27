<!-- Observações Field -->
<div class="form-group col-sm-12">
	{!! Form::label('observacao', 'Observações:') !!}
	{!! Form::textarea('observacao', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('comprador', 'comprador:') !!}
	{!! Form::select('comprador', [''=>'Escolha...'], NULL, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.approve') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	{!! Form::button( '<i class="fa fa-times"></i> '. ucfirst( trans('common.repprove') ), ['class' => 'btn btn-error pull-right', 'type'=>'submit', 'style' => 'margin: 0 5px;']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop