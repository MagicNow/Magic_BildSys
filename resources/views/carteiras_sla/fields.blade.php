<!-- Obra ID Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[], @isset($carteirasSla) ? $carteirasSla->obra_id : null, ['class' => 'form-control select2']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carteira_id', 'Carteira:') !!}
    {!! Form::select('carteira_id',[], @isset($carteirasSla) ? $carteirasSla->carteira_id : null, ['class' => 'form-control select2']) !!}
</div>

<!-- Obra Início Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_inicio', 'Obra Inínio:') !!}
    {!! Form::date('obra_inicio', null, ['class' => 'form-control']) !!}
</div>

<!-- Data subir QC Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_subir_qc', 'Data subir QC:') !!}
    {!! Form::date('obra_subir_qc', null, ['class' => 'form-control']) !!}
</div>

<!-- Data aprovar QC Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_aprovar_qc', 'Data aprovar QC:') !!}
    {!! Form::date('obra_aprovar_qc', null, ['class' => 'form-control']) !!}
</div>

<!-- Data finalizar QC Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_finalizar_qc', 'Data finalizar QC:') !!}
    {!! Form::date('obra_finalizar_qc', null, ['class' => 'form-control']) !!}
</div>

<!-- Data início obras Field -->
<div class="form-group col-sm-6">
    {!! Form::label('inicio_atividade', 'Data início atividades:') !!}
    {!! Form::date('inicio_atividade', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('carteiras_sla.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script>
        $(function () {
            $('#obra_id').select2({
                theme:'bootstrap',
                allowClear: true,
                placeholder: "Escolha...",
                language: "pt-BR",

                ajax: {
                    url: "{{ route('buscar.insumo-grupos') }}",
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
