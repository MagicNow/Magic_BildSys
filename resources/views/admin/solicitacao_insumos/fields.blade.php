<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Unidades Sigla Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unidade_sigla', 'Sigla:') !!}
    {!! Form::text('unidade_sigla', null, ['class' => 'form-control']) !!}
</div>

<!-- Codigo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('codigo', 'Código:') !!}
    {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
</div>

<!-- Insumo Grupo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('insumo_grupo_id', 'Grupo:') !!}
    {!! Form::select('insumo_grupo_id',[]+$insumo_grupos, @isset($solicitacaoInsumo) ? $solicitacaoInsumo->insumo_grupo_id : null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.solicitacaoInsumos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script>
        $(function () {
            $('#insumo_grupo_id').select2({
                allowClear: true,
                placeholder: "Escolha...",
                language: "pt-BR",

                ajax: {
                    url: "{{ url('/admin/solicitacaoInsumos/buscar/grupos_insumos') }}",
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