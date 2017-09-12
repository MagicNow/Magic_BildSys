<!-- nome Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Obra Id Field -->
<div class="form-group col-sm-4">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control']) !!}
</div>

<!-- Tipo Orçamento Id Field -->
<div class="form-group col-sm-3">
    {!! Form::label('orcamento_tipo_id', 'Tipo Orçamento:') !!}
	{!! Form::select('orcamento_tipo_id', ['' => 'Escolha...']+$orcamento_tipos, null, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<?php
$count_insumos = 0;
?>
<div>

    <div>
        <div class="col-md-12">
            <h2 style="border-bottom: 2px solid #ccc !important;margin-bottom: 20px;">Insumos</h2>
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
                            <div class="col-md-12 border-separation"
                                    {{@isset(array_count_values($array_insumos)[$item->insumo_id]) ?
                                    'style=display:none;' :
                                    'style=margin-bottom:20px;'}}></div>
                            @if(@isset(array_count_values($array_insumos)[$item->insumo_id]) && $botao_insumo_id != $item->insumo_id)
                                @php
                                    $botao_insumo_id = $item->insumo_id;
                                @endphp
                                <button class="btn btn-warning flat pull-right" type="button"
                                        onclick="mostrarReajustes('{{$item->insumo_id}}', 1)"
                                        id="btn_mostrar_ocultar_{{$item->insumo_id}}"
                                        title="Mostrar/Ocultar todos os reajustes">
                                    <i class="fa fa-plus" id="icon_mostrar_ocultar_{{$item->insumo_id}}"></i>
                                    Mostrar/Ocultar todos os reajustes
                                </button>
                            @endif
                        @endif


                        <div class="col-md-10" {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none;' : ''}}>
                            <label>Insumo:</label>
                            @if($podeEditar)
                                {!! Form::hidden('contratoInsumos['.$item->id.'][id]', $item->id) !!}
                                {!! Form::select('contratoInsumos['.$item->id.'][insumo_id]',[''=>'Escolha...']+
                                \App\Models\Insumo::select([
                                                        'id',
                                                        DB::raw("CONCAT(codigo, ' - ', nome, ' - ', unidade_sigla) as nome")
                                                    ])
                                                    ->where('id',$item->insumo_id)->pluck('nome','id')->toArray(),
                                $item->insumo_id,
                                [
                                    'class' => 'form-control select2 insumos_existentes insumo_select_'.$item->id,
                                    'required'=>'required',
                                    'id' => 'insumo_select_'.$item->id
                                ]) !!}
                            @else
                                <div class="form-control">
                                    {{ $item->insumo->codigo }} - {{ $item->insumo->nome }}
                                    - {{ $item->insumo->unidade_sigla }}
                                </div>
                            @endif
                        </div>
						
                        <div class="col-md-2" style="margin-top:25px;{{in_array($item->insumo_id, $array_insumos) ? 'display:none;' : ''}}">                            

                            <div class="col-md-3">
                                @if($podeEditar)
                                    <button type="button" onclick="deleteInsumo({{$item->id}})"
                                            class="btn btn btn-danger flat" aria-label="Close" title="Remover">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none; class=bloco_mostrar_reajustes_'.$item->insumo_id : ''}}>

                            <div class="col-md-12 border-separation"
                                 style="border-bottom: 1px solid #d2d6de !important; margin-bottom: 20px;"></div>

                            <div class="col-md-12">
                                <p class="pull-right">
                                    {{count($insumo) > 1 ? 'Alterado' : 'Criado'}}
                                    por {{$item->user ? $item->user->name : null}}
                                    em {{$item->created_at->format('d/m/Y H:i')}}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label>Valor unitário:</label>
                                iv>
                            
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

    <div id="add_insumos" class="col-md-3" style="margin-bottom:25px;margin-top:25px">
        <span class="btn btn-info btn-lg btn-flat btn-block" onclick="addInsumo()">
            <i class="fa fa-plus"></i> Adicionar insumo
        </span>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">    
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ),
    ['class' => 'btn btn-success pull-right btn-lg btn-flat', 'type'=>'submit']) !!}
    <a href="{!! route('mascara_padrao.index') !!}" class="btn btn-default btn-lg btn-flat"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var count_insumos = '{{$count_insumos}}';
        var count_reajuste = '{{$count_insumos}}';

        function addInsumo() {
            @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
            swal({
                        title: "Inserir um insumo?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, insira um insumo!",
                        cancelButtonText: "Não",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            swal.close();
                            @endif
                                    count_insumos++;

                            var block_insumos = '<div class="form-group col-md-12" id="block_insumos' + count_insumos + '">\
                                                <div class="col-md-11">\
                                                <label>Insumo:</label>\
                                                    <select class="form-control insumo_select_' + count_insumos + '" id="insumo_select_' + count_insumos + '" name="contratoInsumos[' + count_insumos + '][insumo_id]" required></select>\
                                                </div>\
                                                <div class="col-md-1" align="right" style="margin-top:25px;">\
                                                    <button type="button" onclick="removeInsumo(' + count_insumos + ')" class="btn btn btn-danger flat" aria-label="Close" title="Remover" >\
                                                        <i class="fa fa-times"></i>\
                                                    </button>\
                                                </div>\
                                                <div class="col-md-3">\
                                                    <label>Coeficiente:</label>\
                                                    <div class="input-group">\
                                                        <input type="text" class="form-control money" id="coeficiente_' + count_insumos + '" name="contratoInsumos[' + count_insumos + '][valor_unitario]">\
                                                    </div>\
                                                </div>\
                                                <div class="col-md-12 border-separation"></div>\
                                            </div>';
                            $("#add_insumos").animate({
                                // distância do topo
                                marginTop: "200px"
                                // tempo de execucao - milissegundos
                            }, 1000, function () {
                                $('#insumos').append(block_insumos);

                                setTimeout(function () {

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

                                    $('.money').maskMoney({allowNegative: true, thousands: '.', decimal: ','});

                                }, 100);

                                $('#add_insumos').css('margin-top', '25px');
                            });
                            @if(isset($catalogoContrato) && $catalogoContrato->catalogo_contrato_status_id == 3)
                        }
                    });
            @endif
        }

        function removeInsumo(what) {
            $('#block_insumos' + what).slideUp('slow', function () {
                $('#block_insumos' + what).remove();
            });
        }

        function removeInsumoId(what) {
            $('.bloco_insumos_id_' + what).slideUp('slow', function () {
                $('.bloco_insumos_id_' + what).remove();
            });
        }

        function deleteInsumo(what) {
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
                    function () {
                        $.ajax({
                            url: "/catalogo-acordos-insumo/delete",
                            data: {insumo: what}
                        }).done(function (retorno) {
                            if (retorno.sucesso) {
                                removeInsumoId(retorno.insumo_id);
                                swal(retorno.resposta.toString());
                            } else {
                                swal(retorno.resposta.toString());
                            }
                        });
                    });
        }

        function formatInsumoResultSelection(obj) {
            if (obj.nome) {
                return obj.nome;
            }
            return obj.text;
        }

        function formatInsumoResult(obj) {
            if (obj.loading) return obj.text;

            var markup_insumo = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup_insumo;
        }

        function formatResult(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelection(obj) {
            if (obj.agn_st_nome) {
                return obj.agn_st_nome;
            }
            return obj.text;
        }

        $(function () {
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
                placeholder: "-",
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
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page

            });
        });

	</script>
@endsection