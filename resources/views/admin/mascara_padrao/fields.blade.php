<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- tipo de orçamentos Id Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipo_orcamentos_id', 'Tipo de Orçamentos:') !!}
	{!! Form::select('tipo_orcamentos_id', $tipo_orcamentos, null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.carteiras.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
	
		function formatResult (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.name + "</div>"+
                "   </div>"+
                "</div>";

            return markup;
        }

        function formatResultSelection (obj) {
            if(obj.name){
                return obj.name;
            }
            return obj.text;
        }

        function formatResultTipoOrcamento (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome +' - ' + obj.uf  +
                    "</div>"+
                    "   </div>"+
                    "</div>";

            return markup;
        }

        function formatResultSelectionTipoOrcamento (obj) {
            if(obj.nome){
                //return obj.nome + ' - '+ obj.uf;
				return obj.nome;
            }
            return obj.text;
        }
        
		$('#tipo_orcamento_id').select2({
                language: "pt-BR",
                theme: "bootstrap",
                placeholder: 'Digite o Tipo de Orçamento...',
                allowClear: true,
                ajax: {
                    url: "/busca-tipo-orcamentos",
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
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatResultTipoOrcamento, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelectionTipoOrcamento // omitted for brevity, see the source of this page
            });
		
    </script>
@stop
