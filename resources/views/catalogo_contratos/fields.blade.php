<!-- Arquivo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arquivo', 'Arquivo:') !!}
    @if(@isset($catalogoContrato))
        @if($catalogoContrato->arquivo)
            <a href="{{$catalogoContrato->arquivo}}" download>Baixar arquivo</a>
        @endif
    @endif
    {!! Form::file('arquivo', null, ['class' => 'form-control']) !!}
</div>

<!-- Fornecedores Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fornecedor_cod', 'Fornecedor:') !!}
    {!! Form::select('fornecedor_cod', ['' => 'Escolha...']+$fornecedores, @isset($catalogoContrato) ? $catalogoContrato->fornecedor->codigo_mega : null, ['class' => 'form-control','id'=>'fornecedor_cod','required'=>'required']) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data', 'Data:') !!}
    {!! Form::date('data', @isset($catalogoContrato) ? $catalogoContrato->data ? $catalogoContrato->data->format('Y-m-d') : null : null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::text('valor', null, ['class' => 'form-control money','required'=>'required']) !!}
</div>

<!-- Período início Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_inicio', 'Período início:') !!}
    {!! Form::date('periodo_inicio', @isset($catalogoContrato) ? $catalogoContrato->periodo_inicio ? $catalogoContrato->periodo_inicio->format('Y-m-d') : null : null, ['class' => 'form-control']) !!}
</div>

<!-- Período término Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_termino', 'Período término:') !!}
    {!! Form::date('periodo_termino', @isset($catalogoContrato) ? $catalogoContrato->periodo_termino ? $catalogoContrato->periodo_termino->format('Y-m-d') : null : null, ['class' => 'form-control']) !!}
</div>

<!-- Valor mínimo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_minimo', 'Valor mínimo:') !!}
    {!! Form::text('valor_minimo', null, ['class' => 'form-control money']) !!}
</div>

<!-- Valor máximo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor_maximo', 'Valor máximo:') !!}
    {!! Form::text('valor_maximo', null, ['class' => 'form-control money']) !!}
</div>

<!-- Quantidade mínima Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd_minima', 'Quantidade mínima:') !!}
    {!! Form::text('qtd_minima', null, ['class' => 'form-control money']) !!}
</div>

<!-- Quantidade máxima Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd_maxima', 'Quantidade máxima:') !!}
    {!! Form::text('qtd_maxima', null, ['class' => 'form-control money']) !!}
</div>

<?php
$count_insumos = 0;
?>

<div class="modal-header">
    <div class="col-md-12">
        <h2>Insumos</h2>
    </div>
</div>

@if(isset($catalogoContrato))
    @foreach ($catalogoContrato->contratoInsumos as $insumo )
        <?php
        $count_insumos = $insumo->id;
        ?>
        <div class="form-group col-md-12" id="block_insumos{{$insumo->id}}">
            {!! Form::hidden('insumos['.$insumo->id.'][id]', $insumo->id) !!}
            <div class="col-md-11">
                <label>Insumo:</label>
                {!! Form::select('insumos['.$insumo->id.'][insumo_id]',[''=>'Escolha...']+ \App\Models\Insumo::where('id',$insumo->insumo_id)->pluck('nome','id')->toArray(), $insumo->insumo_id, ['class' => 'form-control select2 insumos_existentes insumo_select_'.$insumo->id,'required'=>'required', 'id' => 'insumo_select_'.$insumo->id]) !!}
            </div>
            <div class="col-md-1" align="right" style="margin-top:25px;">
                <button type="button" onclick="deleteInsumo({{$insumo->id}})" class="btn btn btn-danger" aria-label="Close" title="Remover" >
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="col-md-3">
                <label>Valor unitário:</label>
                <input type="text" value="{{$insumo->valor_unitario}}" id="valor_unitario_{{$insumo->id}}" class="form-control money" name="insumos[{{$insumo->id}}][valor_unitario]">
            </div>
            <div class="col-md-3">
                <label>Valor máximo:</label>
                <input type="text" value="{{$insumo->valor_maximo}}" id="valor_maximo_{{$insumo->id}}" class="form-control money" name="insumos[{{$insumo->id}}][valor_maximo]">
            </div>
            <div class="col-md-3">
                <label>Pedido mínimo:</label>
                <input type="text" value="{{$insumo->pedido_minimo}}" id="pedido_minimo_{{$insumo->id}}" class="form-control decimal" name="insumos[{{$insumo->id}}][pedido_minimo]">
            </div>
            <div class="col-md-3">
                <label>Pedido múltiplo de:</label>
                <input type="text" value="{{$insumo->pedido_multiplo_de}}" id="pedido_multiplo_de_{{$insumo->id}}" class="form-control decimal" name="insumos[{{$insumo->id}}][pedido_multiplo_de]">
            </div>
            <div class="col-md-12 border-separation"></div>
        </div>
    @endforeach
@endif
<div id="insumos"></div>
<div id="add_insumos" class="col-md-6 col-md-offset-3" style="margin-bottom:25px;margin-top:25px">
    <span class="btn btn-info btn-lg btn-flat btn-block" onclick="addInsumo()">
        <i class="fa fa-plus"></i> Adicionar insumo
    </span>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right btn-lg btn-flat', 'type'=>'submit']) !!}
    <a href="{!! route('catalogo_contratos.index') !!}" class="btn btn-default btn-lg btn-flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
<script type="text/javascript">
    var count_insumos = '{{$count_insumos}}';

    function addInsumo(){
        count_insumos++;

        var block_insumos = '<div class="form-group col-md-12" id="block_insumos'+count_insumos+'">\
                                <div class="col-md-11">\
                                <label>Insumo:</label>\
                                    <select class="form-control insumo_select_'+count_insumos+'" id="insumo_select_'+count_insumos+'" name="insumos['+count_insumos+'][insumo_id]" required></select>\
                                </div>\
                                <div class="col-md-1" align="right" style="margin-top:25px;">\
                                    <button type="button" onclick="removeInsumo('+count_insumos+')" class="btn btn btn-danger" aria-label="Close" title="Remover" >\
                                        <i class="fa fa-times"></i>\
                                    </button>\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Valor unitário:</label>\
                                    <input type="text" class="form-control money" id="valor_unitario_'+count_insumos+'" name="insumos['+count_insumos+'][valor_unitario]">\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Valor máximo:</label>\
                                    <input type="text" class="form-control money" id="valor_maximo_'+count_insumos+'" name="insumos['+count_insumos+'][valor_maximo]">\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Pedido mínimo:</label>\
                                    <input type="text" class="form-control decimal" id="pedido_minimo_'+count_insumos+'" name="insumos['+count_insumos+'][pedido_minimo]">\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Pedido múltiplo de:</label>\
                                    <input type="text" class="form-control decimal" id="pedido_multiplo_de_'+count_insumos+'" name="insumos['+count_insumos+'][pedido_multiplo_de]">\
                                </div>\
                                <div class="col-md-12 border-separation"></div>\
                            </div>';
        $("#add_insumos").animate({
            // distância do topo
            marginTop: "200px"
            // tempo de execucao - milissegundos
        }, 1000, function() {
            $('#insumos').append(block_insumos);

            setTimeout(function() {
                $('.insumo_select_' + count_insumos).select2({
                    allowClear: true,
                    placeholder: "Escolha...",
                    language: "pt-BR",

                    ajax: {
                        url: "{{ route('catalogo_contratos.busca_insumos') }}",
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

                $('.money').maskMoney({allowNegative: true, thousands:'.', decimal:','});

            }, 100);

            $('#add_insumos').css('margin-top','25px');
        });
    }

    function removeInsumo(what){
        $('#block_insumos'+what).slideUp('slow', function(){$('#block_insumos'+what).remove(); });
    }

    function deleteInsumo(what){
        swal({
            title: "Você tem certeza?",
            text: "Você não poderá mais recuperar este registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sim, Remover",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                url: "/catalogo-acordos-insumo/delete",
                data: {insumo : what}
            }).done(function(retorno) {
                if(retorno.sucesso){
                    removeInsumo(what);
                    swal(retorno.resposta.toString());
                }else{
                    swal(retorno.resposta.toString());
                }
            });
        });
    }

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

    function formatResult (obj) {
        if (obj.loading) return obj.text;

        var markup =    "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>"+
                "   </div>"+
                "</div>";

        return markup;
    }

    function formatResultSelection (obj) {
        if(obj.agn_st_nome){
            return obj.agn_st_nome;
        }
        return obj.text;
    }

    $(function(){
        $('.insumos_existentes').select2({
            allowClear: true,
            placeholder: "Escolha...",
            language: "pt-BR",

            ajax: {
                url: "{{ route('catalogo_contratos.busca_insumos') }}",
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

        $('#fornecedor_cod').select2({
            allowClear: true,
            placeholder:"-",
            language: "pt-BR",
            ajax: {
                url: "{{ route('catalogo_contratos.busca_fornecedores') }}",
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
@endsection


