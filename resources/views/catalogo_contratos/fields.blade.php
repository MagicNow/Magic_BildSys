<!-- Fornecedores Field -->
<div class="form-group col-sm-12">
    {!! Form::label('fornecedor_cod', 'Fornecedor:') !!}
    {!! Form::select('fornecedor_cod', ['' => 'Escolha...']+$fornecedores, @isset($catalogoContrato) ? $catalogoContrato->fornecedor->codigo_mega : null, ['class' => 'form-control','id'=>'fornecedor_cod','required'=>'required']) !!}
</div>

<?php
$count_insumos = 0;
?>

<div class="modal-header" style="border-bottom: 2px solid #ccc !important;margin-bottom: 20px;">
    <div class="col-md-12">
        <h2>Insumos</h2>
    </div>
</div>

@if(isset($catalogoContrato))
    @php
        $array_insumos = [];
        $botao = 1;
    @endphp
    @foreach ($catalogoContrato->contratoInsumos->sortByDesc('id')->groupBy('insumo_id') as $insumo)
        @foreach($insumo as $item)
            @php $count_insumos = $item->id; @endphp
            <div class="form-group col-md-12" id="block_insumos{{$item->id}}">
                @if(count($array_insumos))
                    <div class="col-md-12 border-separation" {{@isset(array_count_values($array_insumos)[$item->insumo_id]) ? 'style=display:none;' : 'style=margin-bottom:20px;'}}></div>
                    @if(@isset(array_count_values($array_insumos)[$item->insumo_id]) && $botao)
                        @php $botao = 0; @endphp
                        <button class="btn btn-warning flat pull-right" type="button" onclick="mostrarReajustes('{{$item->insumo_id}}', 1)" id="btn_mostrar_ocultar_{{$item->insumo_id}}" title="Mostrar/Ocultar todos os reajustes">
                            <i class="fa fa-plus" id="icon_mostrar_ocultar_{{$item->insumo_id}}"></i>
                        </button>

                        <div class="bloco_mostrar_reajustes_{{$item->insumo_id}}" style="display: none;"> {{--DIV dinâmica--}}
                    @else
                        <div class="bloco_mostrar_reajustes_{{$item->insumo_id}}" {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none;' : ''}}> {{--DIV dinâmica--}}
                    @endif
                @endif

                {!! Form::hidden('insumos['.$item->id.'][id]', $item->id) !!}
                <div class="col-md-10" {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none;' : ''}}>
                    <label>Insumo:</label>
                    {!! Form::select('insumos['.$item->id.'][insumo_id]',[''=>'Escolha...']+ \App\Models\Insumo::where('id',$item->insumo_id)->pluck('nome','id')->toArray(), $item->insumo_id, ['class' => 'form-control select2 insumos_existentes insumo_select_'.$item->id,'required'=>'required', 'id' => 'insumo_select_'.$item->id]) !!}
                </div>
                <div class="col-md-2" style="margin-top:25px;{{in_array($item->insumo_id, $array_insumos) ? 'display:none;' : ''}}">
                    <div class="col-md-9">
                        <button class="btn btn-success flat pull-right" type="button">
                            Reajuste
                        </button>
                    </div>

                    <div class="col-md-3">
                        <button type="button" onclick="deleteInsumo({{$item->id}})" class="btn btn btn-danger flat" aria-label="Close" title="Remover" >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12 border-separation" style="border-bottom: 1px solid #d2d6de !important; margin-bottom: 20px;"></div>
                <div class="col-md-3">
                    <label>Valor unitário:</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">R$</span>
                        <input type="text" value="{{$item->valor_unitario}}" id="valor_unitario_{{$item->id}}" class="form-control money" name="insumos[{{$item->id}}][valor_unitario]">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Pedido quantidade mínima:</label>
                    <input type="text" value="{{$item->pedido_minimo}}" id="pedido_minimo_{{$item->id}}" class="form-control decimal" name="insumos[{{$item->id}}][pedido_minimo]">
                </div>
                <div class="col-md-2">
                    <label>Pedido múltiplo de:</label>
                    <input type="text" value="{{$item->pedido_multiplo_de}}" id="pedido_multiplo_de_{{$item->id}}" class="form-control decimal" name="insumos[{{$item->id}}][pedido_multiplo_de]">
                </div>
                <div class="col-md-2">
                    <label>Período início:</label>
                    <input type="date" value="{{$item->periodo_inicio ? $item->periodo_inicio->format('Y-m-d') : null}}" id="periodo_inicio_{{$item->id}}" class="form-control" name="insumos[{{$item->id}}][periodo_inicio]">
                </div>
                <div class="col-md-2">
                    <label>Período término:</label>
                    <input type="date" value="{{$item->periodo_termino ? $item->periodo_termino->format('Y-m-d') : null}}" id="periodo_termino_{{$item->id}}" class="form-control" name="insumos[{{$item->id}}][periodo_termino]">
                </div>
            </div> {{--DIV dinâmica--}}
            </div>
            @php $array_insumos[] = $item->insumo_id; @endphp
        @endforeach
    @endforeach
@endif

<div class="col-md-12 border-separation"></div>

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
                                    <div class="input-group">\
                                        <span class="input-group-addon" id="basic-addon1">R$</span>\
                                        <input type="text" class="form-control money" id="valor_unitario_'+count_insumos+'" name="insumos['+count_insumos+'][valor_unitario]">\
                                    </div>\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Pedido quantidade mínima:</label>\
                                    <input type="text" class="form-control decimal" id="pedido_minimo_'+count_insumos+'" name="insumos['+count_insumos+'][pedido_minimo]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Pedido múltiplo de:</label>\
                                    <input type="text" class="form-control decimal" id="pedido_multiplo_de_'+count_insumos+'" name="insumos['+count_insumos+'][pedido_multiplo_de]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Período início:</label>\
                                    <input type="date" class="form-control" id="periodo_inicio_'+count_insumos+'" name="insumos['+count_insumos+'][periodo_inicio]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Período término:</label>\
                                    <input type="date" class="form-control" id="periodo_termino_'+count_insumos+'" name="insumos['+count_insumos+'][periodo_termino]">\
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

    function mostrarReajustes(item, mostrar) {
        if(mostrar){
            $('.bloco_mostrar_reajustes_'+item).css('display', '');
            $('#btn_mostrar_ocultar_'+item).attr('onclick', 'mostrarReajustes('+item+', 0)');
            $('#icon_mostrar_ocultar_'+item).attr('class', 'fa fa-minus');
        }else{
            $('.bloco_mostrar_reajustes_'+item).css('display', 'none');
            $('#btn_mostrar_ocultar_'+item).attr('onclick', 'mostrarReajustes('+item+', 1)');
            $('#icon_mostrar_ocultar_'+item).attr('class', 'fa fa-plus');
        }
    }
</script>
@endsection