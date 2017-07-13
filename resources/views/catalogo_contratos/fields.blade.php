<!-- Fornecedores Field -->
<div class="row">
    <div class="form-group col-sm-6">
        {!! Form::label('fornecedor_cod', 'Fornecedor:') !!}
        @if(isset($catalogoContrato))
            <div class="form-control">
                {{ $catalogoContrato->fornecedor->nome }}
            </div>
        @else
            {!! Form::select('fornecedor_cod', ['' => 'Escolha...']+$fornecedores,  null, ['class' => 'form-control','id'=>'fornecedor_cod','required'=>'required']) !!}
        @endif
    </div>

    <div class="form-group col-sm-6">
        @if(isset($catalogoContrato))
            @if($catalogoContrato->catalogo_contrato_status_id == 2 ||  $catalogoContrato->catalogo_contrato_status_id == 3 && $catalogoContrato->obras()->whereIn('catalogo_contrato_status_id',[1,2])->count()  )
                <div class="col-sm-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            Enviar minuta assinada
                        </div>
                        <div class="box-body">
                            @if(strlen($catalogoContrato->minuta_assinada))
                                <a href="{{ Storage::url($catalogoContrato->minuta_assinada) }}" target="_blank"
                                   class="btn btn-info btn-xs btn-flat btn-block">
                                    <i class="fa fa-download"></i> Baixar Minuta já assinada
                                </a>
                            @endif
                            {!! Form::file('minuta_assinada',['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            @else
                @if(strlen($catalogoContrato->minuta_assinada))
                    <a href="{{ Storage::url($catalogoContrato->minuta_assinada) }}" target="_blank"
                       class="btn btn-info btn-xs btn-flat btn-block">
                        <i class="fa fa-download"></i> Baixar Minuta já assinada
                    </a>
                @endif
            @endif
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Campos extras Minuta de Acordo</h4>
        <?php
            $contratoTemplateMinuta = \App\Models\ContratoTemplate::where('tipo','A')->first(); // busca o de Acordo
            if( strlen(trim($contratoTemplateMinuta->campos_extras)) ){
                $campos_extras = json_decode($contratoTemplateMinuta->campos_extras);
            }

            $valores_campos_extras_minutas = null;
            if(isset($catalogoContrato)){
                $valores_campos_extras_minutas = json_decode($catalogoContrato->campos_extras_minuta);
            }
        ?>
        @if($campos_extras)
            <table class="table table-condensed table-hovered table-striped table-bordered">
                <thead>
                <th width="40%">Campo</th>
                <th width="40%">Valor</th>
                <th width="20%">Tipo</th>
                </thead>
                <tbody>
                @foreach($campos_extras as $campo => $valor)
                    <?php
                    $v_tag = 'CAMPO_EXTRA_MINUTA['. str_replace(']','', str_replace('[','', $valor->tag )). ']' ;
                    $tag =  str_replace(']','', str_replace('[','', $valor->tag )) ;
                    ?>
                    <tr>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->nome }}</label>
                        </td>
                        <td>
                            <input type="text" class="form-control"
                                   value="{{ isset($valores_campos_extras_minutas->$tag)?$valores_campos_extras_minutas->$tag:null }}"
                                   required="required" name="{{ $v_tag }}" placeholder="{{ $valor->nome }}">
                        </td>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->tipo }}</label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
    <div class="col-md-6">
        <h4>Campos extras Quando for gerado um Contrato Automático</h4>
        <?php
        $contratoTemplateContrato = \App\Models\ContratoTemplate::where('tipo','M')->first(); // busca o de Acordo
        if( strlen(trim($contratoTemplateContrato->campos_extras)) ){
            $campos_extras = json_decode($contratoTemplateContrato->campos_extras);
        }

        $valores_campos_extras_contratos = null;
        if(isset($catalogoContrato)){
            $valores_campos_extras_contratos = json_decode($catalogoContrato->campos_extras_contrato);
        }
        ?>
        @if($campos_extras)
            <table class="table table-condensed table-hovered table-striped table-bordered">
                <thead>
                <th width="40%">Campo</th>
                <th width="40%">Valor</th>
                <th width="20%">Tipo</th>
                </thead>
                <tbody>
                @foreach($campos_extras as $campo => $valor)
                    <?php
                    $v_tag = 'CAMPO_EXTRA_CONTRATO['. str_replace(']','', str_replace('[','', $valor->tag )). ']' ;
                    $tag =  str_replace(']','', str_replace('[','', $valor->tag )) ;
                    ?>
                    <tr>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->nome }}</label>
                        </td>
                        <td>
                            <input type="text"
                                   value="{{ isset($valores_campos_extras_contratos->$tag)?$valores_campos_extras_contratos->$tag:null }}"
                                   class="form-control" required="required" name="{{ $v_tag }}" placeholder="{{ $valor->nome }}">
                        </td>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->tipo }}</label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>

<div class="col-sm-12">
    <h4>Obras que estão permitidas neste acordo</h4>
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">Escolha uma obra e adicione</span>
        @if(isset($catalogoContrato))
            {!! Form::select('obra_selecionada', ['' => 'Escolha...']+\App\Models\Obra::whereNotIn('id',$catalogoContrato->obras()->pluck('obra_id','obra_id')->toArray())->pluck('nome','id')->toArray(),  null, ['class' => 'form-control select2','id'=>'obra_selecionada']) !!}
        @else
            {!! Form::select('obra_selecionada', ['' => 'Escolha...']+\App\Models\Obra::pluck('nome','id')->toArray(),  null, ['class' => 'form-control select2','id'=>'obra_selecionada']) !!}
        @endif
        <span class="input-group-btn">
            <button type="button" class="btn btn-primary btn-flat" onclick="adicionaObra();">Adicionar obra</button>
        </span>
    </div>
    {{ Form::hidden('qtd_obras',(!isset($catalogoContrato)?0:$catalogoContrato->obras()->count()),['id'=>'qtd_obras']) }}

    <ul class="list-group" id="obra_list">
        <?php
        $count_obras = 0;
        ?>
        @if(isset($catalogoContrato))
            @foreach($catalogoContrato->obras as $cc_obra)
                <li class="list-group-item" id="obra_list_{{ $cc_obra->id }}">
                    <input type="hidden" name="obra[{{ $count_obras++ }}]" value="{{ $cc_obra->obra_id }}">
                    <i class="fa fa-building"></i>  {{ $cc_obra->obra->nome }}
                    <span class="label label-default" style="background-color: {{$cc_obra->status->cor}}">{{ $cc_obra->status->nome }}</span>
                    <button type="button" title="Remover Obra" onclick="removerObra({{ $cc_obra->id }},{{ $cc_obra->id }});" class="btn btn-danger btn-xs btn-flat pull-right">
                        <i class="fa fa-times"></i>
                    </button>
                </li>
            @endforeach
        @endif
    </ul>
</div>

<?php
$count_insumos = 0;
?>
<div>

    <div class="modal-header" style="border-bottom: 2px solid #ccc !important;margin-bottom: 20px;">
        <div class="col-md-12">
            <h2>Insumos</h2>
        </div>
    </div>

    @if(isset($catalogoContrato))
        @php
            $array_insumos = [];
            $botao_insumo_id = null;
        @endphp
        @if(count($catalogoContrato->contratoInsumos))
            @foreach ($catalogoContrato->contratoInsumos->sortByDesc('id')->groupBy('insumo_id') as $insumo)
                @foreach($insumo as $item)
                    @php
                        $count_insumos = $item->id;
                        $podeEditar = false;
                        if($catalogoContrato->catalogo_contrato_status_id < 3){
                            // Se a data de inserção deste item for maior que a data de alteração para status Ativo, libera a edição
                            $podeEditar = true;
                        }
                    @endphp
                    <div class="form-group col-md-12 bloco_insumos_id_{{$item->insumo_id}}">

                        @if(count($array_insumos))
                            <div class="col-md-12 border-separation" {{@isset(array_count_values($array_insumos)[$item->insumo_id]) ? 'style=display:none;' : 'style=margin-bottom:20px;'}}></div>
                            @if(@isset(array_count_values($array_insumos)[$item->insumo_id]) && $botao_insumo_id != $item->insumo_id)
                                @php
                                    $botao_insumo_id = $item->insumo_id;
                                @endphp
                                <button class="btn btn-warning flat pull-right" type="button" onclick="mostrarReajustes('{{$item->insumo_id}}', 1)" id="btn_mostrar_ocultar_{{$item->insumo_id}}" title="Mostrar/Ocultar todos os reajustes">
                                    <i class="fa fa-plus" id="icon_mostrar_ocultar_{{$item->insumo_id}}"></i> Mostrar/Ocultar todos os reajustes
                                </button>
                            @endif
                        @endif


                        <div class="col-md-10" {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none;' : ''}}>
                            <label>Insumo:</label>
                            @if($podeEditar)
                                {!! Form::hidden('contratoInsumos['.$item->id.'][id]', $item->id) !!}
                                {!! Form::select('contratoInsumos['.$item->id.'][insumo_id]',[''=>'Escolha...']+
                                \App\Models\Insumo::where('id',$item->insumo_id)->pluck('nome','id')->toArray(), $item->insumo_id,
                                [
                                    'class' => 'form-control select2 insumos_existentes insumo_select_'.$item->id,
                                    'required'=>'required',
                                    'id' => 'insumo_select_'.$item->id
                                ]) !!}
                            @else
                                <div class="form-control">
                                    {{ $item->insumo->nome }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2" style="margin-top:25px;{{in_array($item->insumo_id, $array_insumos) ? 'display:none;' : ''}}">
                            <div class="col-md-9">
                                <button class="btn btn-success btn-flat btn-block" type="button" onclick="inserirReajuste({{$item->insumo_id}})">
                                    Reajuste
                                </button>
                            </div>

                            <div class="col-md-3">
                                @if($podeEditar)
                                    <button type="button" onclick="deleteInsumo({{$item->id}})" class="btn btn btn-danger flat" aria-label="Close" title="Remover" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div id="reajuste_{{$item->insumo_id}}"></div>

                        <div {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none; class=bloco_mostrar_reajustes_'.$item->insumo_id : ''}}>

                            <div class="col-md-12 border-separation" style="border-bottom: 1px solid #d2d6de !important; margin-bottom: 20px;"></div>

                            <div class="col-md-12">
                                <p class="pull-right">
                                    Alterado por {{$item->user ? $item->user->name : null}} em {{$item->updated_at->format('d/m/Y H:i')}}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label>Valor unitário:</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                    <input type="text" {{ $podeEditar?'':' disabled="disabled"' }} value="{{$item->valor_unitario}}" id="valor_unitario_{{$item->id}}" class="form-control money" name="contratoInsumos[{{$item->id}}][valor_unitario]">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Pedido quantidade mínima:</label>
                                <input type="text" {{ $podeEditar?'':' disabled="disabled"' }} value="{{$item->pedido_minimo}}" id="pedido_minimo_{{$item->id}}" class="form-control money" name="contratoInsumos[{{$item->id}}][pedido_minimo]">
                            </div>
                            <div class="col-md-2">
                                <label>Pedido múltiplo de:</label>
                                <input type="text" {{ $podeEditar?'':' disabled="disabled"' }} value="{{$item->pedido_multiplo_de}}" id="pedido_multiplo_de_{{$item->id}}" class="form-control money" name="contratoInsumos[{{$item->id}}][pedido_multiplo_de]">
                            </div>
                            <div class="col-md-2">
                                <label>Período início:</label>
                                <input type="date" {{ $podeEditar?'':' disabled="disabled"' }} value="{{$item->periodo_inicio ? $item->periodo_inicio->format('Y-m-d') : null}}" id="periodo_inicio_{{$item->id}}" class="form-control" name="contratoInsumos[{{$item->id}}][periodo_inicio]">
                            </div>
                            <div class="col-md-2">
                                <label>Período término:</label>
                                <input type="date" {{ $podeEditar?'':' disabled="disabled"' }} value="{{$item->periodo_termino ? $item->periodo_termino->format('Y-m-d') : null}}" id="periodo_termino_{{$item->id}}" class="form-control" name="contratoInsumos[{{$item->id}}][periodo_termino]">
                            </div>
                        </div>
                    </div>
                    @php $array_insumos[] = $item->insumo_id; @endphp
                @endforeach
            @endforeach
        @endif
    @endif

    @if(isset($catalogoContrato))
        @if(count($catalogoContrato->contratoInsumos))
            <div class="col-md-12 border-separation"></div>
        @endif
    @endif

    <div id="insumos"></div>

    <div id="add_insumos" class="col-md-6 col-md-offset-3" style="margin-bottom:25px;margin-top:25px">
        <span class="btn btn-info btn-lg btn-flat btn-block" onclick="addInsumo()">
            <i class="fa fa-plus"></i> Adicionar insumo
        </span>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(!isset($catalogoContrato) || (isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id != 2))
    {!! Form::button( '<i class="fa fa-check-square"></i> Gerar minuta e colocar em validação', [
                            'class' => 'btn btn-warning btn-lg btn-flat pull-right',
                            'value' => '1',
                            'name' => 'gerar_minuta',
                            'style' => 'margin-left:10px',
                            'type'=>'submit']) !!}
    @endif
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right btn-lg btn-flat', 'type'=>'submit']) !!}
    <a href="{!! route('catalogo_contratos.index') !!}" class="btn btn-default btn-lg btn-flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
<script type="text/javascript">
    var count_insumos = '{{$count_insumos}}';
    var count_reajuste = '{{$count_insumos}}';

    function addInsumo(){
        @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
        swal({
                    title: "Inserir um insumo?",
                    text: "Ao inserir um insumo o acordo entrará em modo de aguardando validação, esperando o arquivo com a assinatura de ambos os lados validando todos os acordos firmados!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, insira um insumo!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm) {
                        swal.close();
        @endif
                        count_insumos++;

                        var block_insumos = '<div class="form-group col-md-12" id="block_insumos'+count_insumos+'">\
                                                <div class="col-md-11">\
                                                <label>Insumo:</label>\
                                                    <select class="form-control insumo_select_'+count_insumos+'" id="insumo_select_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][insumo_id]" required></select>\
                                                </div>\
                                                <div class="col-md-1" align="right" style="margin-top:25px;">\
                                                    <button type="button" onclick="removeInsumo('+count_insumos+')" class="btn btn btn-danger flat" aria-label="Close" title="Remover" >\
                                                        <i class="fa fa-times"></i>\
                                                    </button>\
                                                </div>\
                                                <div class="col-md-3">\
                                                    <label>Valor unitário:</label>\
                                                    <div class="input-group">\
                                                        <span class="input-group-addon" id="basic-addon1">R$</span>\
                                                        <input type="text" class="form-control money" id="valor_unitario_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][valor_unitario]">\
                                                    </div>\
                                                </div>\
                                                <div class="col-md-3">\
                                                    <label>Pedido quantidade mínima:</label>\
                                                    <input type="text" class="form-control money" id="pedido_minimo_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][pedido_minimo]">\
                                                </div>\
                                                <div class="col-md-2">\
                                                    <label>Pedido múltiplo de:</label>\
                                                    <input type="text" class="form-control money" id="pedido_multiplo_de_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][pedido_multiplo_de]">\
                                                </div>\
                                                <div class="col-md-2">\
                                                    <label>Período início:</label>\
                                                    <input type="date" class="form-control" id="periodo_inicio_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][periodo_inicio]">\
                                                </div>\
                                                <div class="col-md-2">\
                                                    <label>Período término:</label>\
                                                    <input type="date" class="form-control" id="periodo_termino_'+count_insumos+'" name="contratoInsumos['+count_insumos+'][periodo_termino]">\
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
                        @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
            }
        });
        @endif
    }

    function removeInsumo(what){
        $('#block_insumos'+what).slideUp('slow', function(){$('#block_insumos'+what).remove(); });
    }

    function removeInsumoId(what){
        $('.bloco_insumos_id_'+what).slideUp('slow', function(){$('.bloco_insumos_id_'+what).remove(); });
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
                    removeInsumoId(retorno.insumo_id);
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

    function inserirReajuste(insumo_id) {
        @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
        swal({
                    title: "Inserir reajuste?",
                    text: "Ao inserir um reajuste o acordo entrará em modo de aguardando validação, esperando o arquivo com a assinatura de ambos os lados validando todos os acordos firmados!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, insira um reajuste!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm) {
                        swal.close();
        @endif
                        count_reajuste++;

                        var block_reajuste = '<div class="form-group col-md-12" id="block_reajuste'+count_reajuste+'">\
                                <div class="col-md-12 border-separation" style="border-bottom: 1px solid #d2d6de !important; margin-bottom: 20px;"></div>\
                                <input type="hidden" value="'+insumo_id+'" name="reajuste['+count_reajuste+'][insumo_id]">\
                                <div class="col-md-3">\
                                    <label>Valor unitário:</label>\
                                    <div class="input-group">\
                                        <span class="input-group-addon" id="basic-addon1">R$</span>\
                                        <input type="text" class="form-control money" id="valor_unitario_'+count_reajuste+'" name="reajuste['+count_reajuste+'][valor_unitario]">\
                                    </div>\
                                </div>\
                                <div class="col-md-3">\
                                    <label>Pedido quantidade mínima:</label>\
                                    <input type="text" class="form-control money" id="pedido_minimo_'+count_reajuste+'" name="reajuste['+count_reajuste+'][pedido_minimo]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Pedido múltiplo de:</label>\
                                    <input type="text" class="form-control money" id="pedido_multiplo_de_'+count_reajuste+'" name="reajuste['+count_reajuste+'][pedido_multiplo_de]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Período início:</label>\
                                    <input type="date" class="form-control" id="periodo_inicio_'+count_reajuste+'" name="reajuste['+count_reajuste+'][periodo_inicio]">\
                                </div>\
                                <div class="col-md-2">\
                                    <label>Período término:</label>\
                                    <input type="date" class="form-control" id="periodo_termino_'+count_reajuste+'" name="reajuste['+count_reajuste+'][periodo_termino]">\
                                </div>\
                            </div>';
                        $('#reajuste_'+insumo_id).append(block_reajuste);
                        $('#valor_unitario_'+count_reajuste).maskMoney({allowNegative: true, thousands:'.', decimal:','});
                        $('#pedido_minimo_'+count_reajuste).maskMoney({allowNegative: true, thousands:'.', decimal:','});
                        $('#pedido_multiplo_de_'+count_reajuste).maskMoney({allowNegative: true, thousands:'.', decimal:','});
                        @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
                    }
                });
        @endif

    }

    var contadorObra = {{ $count_obras }};
    function adicionaObra(){
        contadorObra++;
        $('#qtd_obras').val( parseInt($('#qtd_obras').val())+1);
        if(!$('#obra_selecionada').val()){
            swal('Escolha uma obra!','', 'error');
            return false;
        }
        obra_selecionada = $('#obra_selecionada').val();
        obra_selecionada_txt = $("#obra_selecionada option:selected").text();
        $('#obra_selecionada').val(null).trigger("change");
        var novaObra = '<li class="list-group-item" id="obra_list_'+contadorObra+'">'+
                '<i class="fa fa-building"></i>  '+ obra_selecionada_txt +
                        '<input type="hidden" name="obra[]" value="'+obra_selecionada+'">'+
        ' <span class="label label-info">Nova</span>'+
                '<button type="button" title="Remover Obra" onclick="removerObra('+contadorObra+',null);" class="btn btn-danger btn-xs btn-flat pull-right">'+
                '<i class="fa fa-times"></i>'+
                '</button>'+
                '</li>';
        $('#obra_list').append(novaObra);

    }
    function removerObra(qual, registroBD) {
        swal({
                    title: "Tem certeza que deseja remover esta obra?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    showLoaderOnConfirm: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Remover',
                    confirmButtonColor: '#DD6B55'
                },
                function() {
                    $('#qtd_obras').val( parseInt($('#qtd_obras').val())-1);
                    if(registroBD){

                        $.ajax({
                            url: '/catalogo-acordos/{{ isset($catalogoContrato)? $catalogoContrato->id: null }}/removeObra/'+registroBD,
                        }).done(function () {
                            $('#obra_list_'+qual).remove();
                            swal({
                                title:"Obra removida deste acordo!",
                                type: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    }else{
                        $('#obra_list_'+qual).remove();
                        swal({
                            title:"Obra removida deste acordo!",
                            type: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });

    }
</script>
@endsection