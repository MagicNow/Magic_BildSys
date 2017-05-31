<!-- Workflow Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('workflow_tipo_id', 'Tipo:') !!}
    {!!
      Form::select(
        'workflow_tipo_id',
          $tipos,
          null,
          ['class' => 'form-control','required'=>'required', 'id' => 'workflow_tipo_id']
        )
    !!}
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
    <a href="{!! route('admin.workflowAlcadas.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
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

    $(function(){
      var tipoSelector = document.getElementById('workflow_tipo_id');
      var valorMinimo = document.getElementById('valor_minimo');

      tipoSelector.addEventListener('change', function(event) {
        $(valorMinimo)
          .parent()
          .toggleClass(
            'hidden',
            parseInt(tipoSelector.value) !== {{ $workflow_tipo_id_contrato }}
          );
      });

      tipoSelector.dispatchEvent(new Event('change'));



        $('#workflowUsuarios').select2({
            language: "pt-BR",
            theme:'bootstrap',
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
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatResult, // omitted for brevity, see the source of this page
            templateSelection: formatResultSelection // omitted for brevity, see the source of this page
        });
    });
</script>
@stop
