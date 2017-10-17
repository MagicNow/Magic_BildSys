@include('qc.show_fields')

<!-- Observações Field -->
<div class="form-group col-sm-12">
	{!! Form::label('observacao', 'Observações:') !!}
	{!! Form::textarea('observacao', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('user_id', 'comprador:') !!}
	{!! Form::select('user_id', [''=>'Escolha...'] + $compradores, NULL, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.approve') ), ['class' => 'btn btn-success pull-right', 'type' => 'submit', 'value' => 'Em negociação', 'name' => 'status']) !!}
	{!! Form::button( '<i class="fa fa-times"></i> '. ucfirst( trans('common.repprove') ), ['class' => 'btn btn-error pull-right', 'type' => 'submit', 'style' => 'margin: 0 5px;', 'value' => 'Reprovado', 'name' => 'status']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop