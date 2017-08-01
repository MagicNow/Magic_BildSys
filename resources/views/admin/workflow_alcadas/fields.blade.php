<!-- Workflow Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('workflow_tipo_id', 'Tipo:') !!}
    @if(!isset($workflowAlcada))
        <select id="workflow_tipo_id" name="workflow_tipo_id" class="form-control" required>
            <option value="">Escolha...</option>
            @foreach($tipos as $tipo)
                <option
                        value="{{ $tipo->id }}"
                        @if(isset($workflowAlcada))
                        {{ old('workflow_tipo_id', $workflowAlcada->workflow_tipo_id) === $tipo->id ? 'selected' : '' }}
                        @else
                        {{ old('workflow_tipo_id') === $tipo->id ? 'selected' : '' }}
                        @endif
                        data-usa-valor-minimo="{{ $tipo->usa_valor_minimo }}">
                    {{ $tipo->nome }}
                </option>
            @endforeach
        </select>
    @else
        <div class="form-control">
            {{ $workflowAlcada->workflowTipo->nome }}
        </div>
    @endif
</div>

<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Ordem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ordem', 'Ordem:') !!}
    {!! Form::number('ordem', null, ['class' => 'form-control', 'required'=>'required', 'id' => 'ordem']) !!}
</div>

<!-- Dias Prazo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dias_prazo', 'Dias Prazo:') !!}
    {!! Form::number('dias_prazo', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('workflowUsuarios', 'Usuários nesta alçada:') !!}
    {!! Form::select('workflowUsuarios[]', $relacionados ,(!isset($workflowAlcada)? null: $workflowUsuarios_ids), ['class' => 'form-control', 'id'=>"workflowUsuarios", 'multiple'=>"multiple"]) !!}
</div>

<div class="form-group col-sm-6 hidden">
    {!! Form::label('valor_minimo', 'Valor Mínimo') !!}
    {!! Form::text('valor_minimo', null, ['class' => 'form-control money', 'id' => 'valor_minimo']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.workflowAlcadas.index') !!}" class="btn btn-danger"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        function formatResult(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.name + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelection(obj) {
            if (obj.name) {
                return obj.name;
            }
            return obj.text;
        }

        $(function () {
            var valorMinimo = document.getElementById('valor_minimo');

            @if(!isset($workflowAlcada))
            var tipoSelector = document.getElementById('workflow_tipo_id');
            tipoSelector.addEventListener('change', function (event) {
                var option = tipoSelector.querySelector('[value="' + event.currentTarget.value + '"]');
                var usaValorMinimo = option.dataset.usaValorMinimo === "1";

                $(valorMinimo).parent().toggleClass('hidden', !usaValorMinimo);
            });

            tipoSelector.dispatchEvent(new Event('change'));
            @endif

            $('#workflowUsuarios').select2({
                language: "pt-BR",
                theme: 'bootstrap',
                ajax: {
                    url: "/admin/users/busca",
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
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page
            });
        });
    </script>
@stop
