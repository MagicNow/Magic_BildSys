<!-- Obra ID Field -->
@if(Request::get('obra_id'))
	{!! Form::hidden('obra_id', Request::get('obra_id')) !!}
@else
	<div class="form-group col-sm-6">
		{!! Form::label('obra_id', 'Obra:') !!}
		{!! Form::select('obra_id',[''=>'Escolha...']+$obras, NULL, ['class' => 'form-control', 'required'=>'required']) !!}
	</div>
@endif

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipologia', 'Tipologia:') !!}
	{!! Form::select('tipologia', ['' => 'Escolha...', 'Budget' => 'Budget', 'Aditivo' => 'Aditivo', 'Bild Design' => 'Bild Design', 'Getec' => 'Getec'], @isset($qc) ? $qc->tipologia : NULL, ['class' => 'form-control select2']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_id', 'Carteira:') !!}
	{!! Form::select('carteira_id',[], @isset($qc) ? $qc->carteira_id : NULL, ['class' => 'form-control select2']) !!}
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
	{!! Form::label('descricao', 'Descrição do serviço:') !!}
	{!! Form::textarea('descricao', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Valor Pré Orçamento Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento:') !!}
	{!! Form::text('valor_pre_orcamento', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Valor Orçamento Inicial Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial :') !!}
	{!! Form::text('valor_orcamento_inicial', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
   <script>
		$(function () {
			$('#carteira_id').select2({
				theme:'bootstrap',
				allowClear: true,
				placeholder: "Escolha...",
				language: "pt-BR",

				ajax: {
					url: "{{ route('buscar.carteiras') }}",
					dataType: 'json',
					delay: 250,

					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},

					processResults: function (result, params) {
						// parse the results into the format expected by Select2
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data, except to indicate that infinite
						// scrolling can be used
						params.page = params.page || 1;

						return {
							results: result.data,
							pagination: {
								more: (params.page * result.per_page) < result.total
							}
						};
					},
					cache: true
				},
				escapeMarkup: function (markup) {
					return markup;
				}, // let our custom formatter work
				minimumInputLength: 1,
				templateResult: formatInsumoResult, // omitted for brevity, see the source of this page
				templateSelection: formatInsumoResultSelection // omitted for brevity, see the source of this page
			});
		});

		function formatInsumoResultSelection (obj) {
			if(obj.nome){
				return obj.nome;
			}
			return obj.text;
		}

		function formatInsumoResult (obj) {
			if (obj.loading) return obj.text;

			var markup_insumo =    "<div class='select2-result-obj clearfix'>" +
					"   <div class='select2-result-obj__meta'>" +
					"       <div class='select2-result-obj__title'>" + obj.nome + "</div>"+
					"   </div>"+
					"</div>";

			return markup_insumo;
		}
	</script>
@stop