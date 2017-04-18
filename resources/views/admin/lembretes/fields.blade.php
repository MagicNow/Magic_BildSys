<!-- Lembrete Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lembrete_tipo_id', 'Lembrete:') !!}
    {!! Form::select('lembrete_tipo_id',[''=>'Escolha...']+$lembrete_tipos, null, ['class' => 'form-control']) !!}
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dias_prazo_minimo', 'Dias Prazo Minimo:') !!}
    {!! Form::number('dias_prazo_minimo', null, ['class' => 'form-control']) !!}
</div>

<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Dias Prazo Maximo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dias_prazo_maximo', 'Dias Prazo Maximo:') !!}
    {!! Form::number('dias_prazo_maximo', null, ['class' => 'form-control']) !!}
</div>

<!-- Grupos de insumo Field -->
<div class="form-group col-sm-12">
    {!! Form::label('insumo_grupo_id', 'Grupos:') !!}
    {!! Form::select('insumo_grupo_id', ['' => 'Escolha...']+$insumo_grupos, null, ['class' => 'form-control','id'=>'insumo_grupo_id']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.lembretes.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        function formatResult (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>"+
                    "   </div>"+
                    "</div>";

            return markup;
        }

        function formatResultSelection (obj) {
            if(obj.nome){
                return obj.nome;
            }
            return obj.text;
        }

        $(function(){
            $('#insumo_grupo_id').select2({
                allowClear: true,
                placeholder:"-",
                language: "pt-BR",
                ajax: {
                    url: "{{ route('admin.lembretes.busca') }}",
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
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page

            });
        });
    </script>
@stop
