@extends('layouts.front')

@section('content')

    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-7">
                    <span class="pull-left title">
                       <h3>
                           <button type="button" class="btn btn-link" onclick="history.go(-1);">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                           </button>
                           <span>
                           Fechar Ordem de Compra</span>
                       </h3>
                    </span>
                </div>

                <div class="col-md-5 text-right">
                    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-lg btn-flat">
                        Esqueci um item
                    </button>
                    <button type="button" onclick="fechaOC();" class="btn btn-success btn-lg btn-flat">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <style type="text/css">
            #carrinho ul{
                list-style-type: none;
                padding: 0px;
            }
            #carrinho ul li{
                background-color: #ffffff;
                border: solid 1px #dddddd;
                padding: 18px;
                margin-bottom: 12px;
                font-size: 16px;
                font-weight: 500;
                color: #9b9b9b;
            }
            #carrinho ul li .label-bloco{
                font-size: 13px;
                font-weight: bold;
                color: #4a4a4a;
                line-height: 15px;
                margin-bottom: 0px;
                padding-bottom: 0px;
            }
            .label-bloco-limitado{
                width: 72px;
            }
            @media (min-width: 769px){
                .label-bloco-limitado{
                    margin-top: -5px;
                }
            }
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            @media (min-width: 1215px){
                .margem-botao{
                    margin-top: -15px;
                }
            }

            .label-input-file{
                text-transform: none;
            }
            .dados-extras{
                background-color: #fff;
                margin-top: 20px;
            }
            .li-aberto{
                height: auto !important;
            }
            .col-xs-12, .col-xs-6, .col-xs-5, .col-xs-1{
                margin-bottom: 5px;
            }
            .btn-xs{
                overflow: hidden;
            }
        </style>
        <div class="row">
            <div id="carrinho" class="col-md-12">
                <ul>
                    @foreach($itens as $item)
                        <li id="item{{ $item->id }}">
                            <div class="row">
                                <span class="col-md-1 col-sm-1 col-xs-12 text-center borda-direita">
                                    <strong class="visible-xs pull-left">Código:</strong>
                                    {{ $item->insumo->codigo }}
                                </span>
                                <span class="col-md-3 col-sm-3 col-xs-12 text-center borda-direita">
                                    <strong class="visible-xs pull-left">Insumo:</strong>
                                    {{ $item->insumo->nome }}
                                </span>
                                <span class="col-md-1 col-sm-1 col-xs-12 text-center borda-direita">
                                    <strong class="visible-xs pull-left">Unidade:</strong>
                                    {{ $item->unidade_sigla }}
                                </span>
                                <span class="col-md-1 col-sm-1 col-xs-12 text-center borda-direita">
                                    <strong class="visible-xs pull-left">Quantidade:</strong>
                                    {{ number_format($item->qtd, 2, ',','.') }}
                                </span>
                        <span class="col-md-2 col-sm-2 col-xs-5 text-center borda-direita">
                            <label class="label-bloco label-bloco-limitado">Indicar contrato</label>
                            <button type="button" class="btn btn-flat btn-sm btn-default margem-botao" onclick="indicarContrato('{{ $item->insumo->codigo }}')">
                                Selecionar
                            </button>
                        </span>
                        <span class="col-md-3 col-sm-3 col-xs-6 text-center borda-direita">
                            {!! Form::open(['url'=> url('/ordens-de-compra/upload-anexos/'.$item->id)  , 'class'=>'formAnexos', 'files'=>true]) !!}
                            {!! Form::hidden('item_id', $item->id, ['id'=>'item_id_'.$item->id]) !!}
                            <label class="label-bloco label-bloco-limitado">Anexar arquivo</label>
                            <input type="file" multiple data-multiple-caption="{count} arquivos escolhidos"
                                   class="inputfile" id="anexos_{{ $item->id }}" name="anexos[]" />
                            <label for="anexos_{{ $item->id }}" id="label_btn_anexo_{{ $item->id }}" class="btn btn-flat btn-sm btn-default margem-botao label-input-file">
                                <span>Selecionar</span>
                            </label>
                            <button type="submit" class="btn btn-sm btn-primary btn-flat margem-botao" title="Enviar">
                                <i class="fa fa-upload" aria-hidden="true"></i>
                            </button>
                            {!! Form::close() !!}
                        </span>
                        <span class="col-md-1 col-sm-1 col-xs-1 text-center">
                            <button type="button" class="btn btn-flat btn-link"
                                    style="font-size: 18px; margin-top: -7px" onclick="showHideExtra({{ $item->id }})">
                                <i class="icone-expandir fa fa-caret-left" aria-hidden="true"></i>
                            </button>
                        </span>
                            </div>
                            <div class="dados-extras" style="display: none;">
                                <hr>
                                <div class="row">
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center  borda-direita">
                                <label class="label-bloco"><i class="fa fa-paperclip" aria-hidden="true"></i> Anexados</label>
                                <small class="row" id="anexados_{{ $item->id }}">
                                    @if($item->anexos)
                                        @foreach($item->anexos as $anexo)
                                            <div id="anexo_{{ $anexo->id }}">
                                                <span class="col-md-10 col-sm-11 col-xs-10">
                                                    <a href="{{ Storage::url($anexo->arquivo) }}" class="btn btn-xs btn-flat btn-block btn-default" download target="_blank">
                                                        <i class="fa fa-download"></i> <span>{{ substr($anexo->arquivo, strrpos($anexo->arquivo,'/')+1)  }}</span>
                                                    </a>
                                                </span>
                                                <span class="col-md-2 col-sm-1 col-xs-2 text-right">
                                                    <button type="button" onclick="removeAnexo({{ $anexo->id }});" class="btn btn-xs btn-flat btn-danger" target="_blank">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </span>
                                            </div>


                                        @endforeach
                                    @endif

                                </small>

                            </span>
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center  borda-direita">
                                <label class="label-bloco col-md-5">Data estimada de uso</label>
                                <span class="col-md-7">
                                    {!! Form::date('sugestao_data_uso['.$item->id.']', $item->sugestao_data_uso, ['class'=>'form-control', 'onChange'=>"alteraItem(".$item->id.",'sugestao_data_uso', this.value )"] ) !!}
                                </span>
                            </span>
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center">
                                {!! Form::checkbox('emergencial['.$item->id.']',1, $item->emergencial, ['class'=>'form-control ck_emergencial', 'id'=>'emergencial_'.$item->id, 'item_id'=>$item->id ] ) !!}
                                <label class="label-bloco" for="emergencial_{{ $item->id }}">Emergencial</label>
                            </span>
                                </div>
                                <hr>

                                <div class="row">
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center borda-direita">
                                <label class="label-bloco">
                                    Tabela Tems

                                </label>
                                <button type="button" class="btn btn-flat btn-xs btn-warning pull-right"
                                            style="display:none"
                                            onclick="alteraItem({{ $item->id }},'tems', $('#tems_{{ $item->id }}').val() );$('#salvar_tems_{{ $item->id }}').hide();"
                                            id="salvar_tems_{{ $item->id }}" title="salvar">
                                    <i class="fa fa-save"></i>
                                </button>
                                {!! Form::textarea('tems['.$item->id.']', $item->tems, ['class'=>'form-control','rows'=>"4",'id'=>'tems_'.$item->id, 'onChange'=>"exibeBtn('salvar_tems_".$item->id."')"]) !!}
                            </span>
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center borda-direita">
                                <label class="label-bloco">Justificativa de Compra</label>
                                <button type="button" class="btn btn-flat btn-xs btn-warning pull-right"
                                        style="display:none"
                                        onclick="alteraItem({{ $item->id }},'justificativa', $('#justificativa_{{ $item->id }}').val() );$('#salvar_justificativa_{{ $item->id }}').hide();"
                                        id="salvar_justificativa_{{ $item->id }}" title="salvar">
                                    <i class="fa fa-save"></i>
                                </button>
                                {!! Form::textarea('justificativa['.$item->id.']', $item->justificativa, ['class'=>'form-control','rows'=>"4",'id'=>'justificativa_'.$item->id,'onChange'=>"exibeBtn('salvar_justificativa_".$item->id."')"]) !!}
                            </span>
                            <span class="col-md-4 col-sm-12 col-xs-12 text-center">
                                <label class="label-bloco">Observações ao fornecedor</label>
                                <button type="button" class="btn btn-flat btn-xs btn-warning pull-right"
                                        style="display:none"
                                        onclick="alteraItem({{ $item->id }},'obs', $('#obs_{{ $item->id }}').val() );$('#salvar_obs_{{ $item->id }}').hide();"
                                        id="salvar_obs_{{ $item->id }}" title="salvar">
                                    <i class="fa fa-save"></i>
                                </button>
                                {!! Form::textarea('obs['.$item->id.']', $item->obs, ['class'=>'form-control','rows'=>"4", 'id'=>'obs_'.$item->id, 'onChange'=>"exibeBtn('salvar_obs_".$item->id."')"]) !!}
                            </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{--<div class="box-body" id='app'>--}}
            {{--<tabela--}}
                    {{--api-url="/api/listagem-ordens-de-compras"--}}
                    {{--api-filtros="/filter-json-ordem-compra"--}}
                    {{--v-bind:params="{}"--}}
                    {{--v-bind:actions="{filtros: true, status: true, detalhe: true, detalhe_url:'{{ url('/ordens-de-compra/detalhes/') }}'}"--}}
                    {{--v-bind:colunas="[--}}
                    {{--{campo_db: 'id', label: 'núm. o.c'},--}}
                    {{--{campo_db: 'obra', label: 'obra'},--}}
                    {{--{campo_db: 'usuario', label: 'usuário'},--}}
                    {{--{campo_db: 'situacao', label: 'situação'}--}}
                    {{--]">--}}
            {{-->--}}
            {{--</tabela>--}}
        {{--</div>--}}
    </div>

    <div class="pg text-center">
        {{ $itens->links() }}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showHideExtra(qual) {
            if(!$('#item'+qual).hasClass('li-aberto')){
                $('#item'+qual).addClass('li-aberto');
                $('#item'+qual+' .dados-extras').show();
                $('#item'+qual+' .icone-expandir').removeClass('fa-caret-left');
                $('#item'+qual+' .icone-expandir').addClass('fa-caret-down');
            }else{
                $('#item'+qual).removeClass('li-aberto');
                $('#item'+qual+' .dados-extras').hide();
                $('#item'+qual+' .icone-expandir').addClass('fa-caret-left');
                $('#item'+qual+' .icone-expandir').removeClass('fa-caret-down');
            }

        }

        function exibeBtn(qual) {
            $('#'+qual).show();
        }

        var inputs = document.querySelectorAll( '.inputfile' );
        Array.prototype.forEach.call( inputs, function( input )
        {
            var label	 = input.nextElementSibling,
                    labelVal = label.innerHTML;

            input.addEventListener( 'change', function( e )
            {
                var fileName = '';
                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                else
                    fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    label.querySelector( 'span' ).innerHTML = fileName;
                else
                    label.innerHTML = labelVal;
            });
        });

        function indicarContrato(codigo_insumo){
            startLoading();
            $.ajax("{{ url('/ordens-de-compra/carrinho/indicar-contrato') }}", {
                        data: {
                            'codigo_insumo' : codigo_insumo
                        },
                        type: "GET"
                    }
            ).done(function (retorno) {
                stopLoading();
                contratos = '';
                $.each(retorno.contrato_insumo, function (index, value) {
                    contratos += '<p>Valor: ' + value.contrato.valor + '</p>';
                });

                swal({
                    html:true,
                    title: "Indicar contrato",
                    text: contratos
                });

            }).fail(function (retorno) {
                stopLoading();
                erros = '';
                $.each(retorno.responseJSON, function (index, value) {
                    if (erros.length) {
                        erros += '<br>';
                    }
                    erros += value;
                });
                erros = erros.replace('conteudo ','');
                swal({
                    html:true,
                    title: "Oops",
                    text: erros,
                    type: 'error'
                });
            });
        }

        function alteraItem(item_id, campo, valor){
            startLoading();
            $.ajax("{{ url('/ordens-de-compra/altera-item') }}/"+ item_id, {
                        data: {
                            coluna: campo,
                            conteudo: valor
                        },
                        type: "POST"
                    }
            ).done(function (retorno) {
                stopLoading();
                if(retorno.success){
                    swal('Salvo','', 'success');
                }
            }).fail(function (retorno) {
                stopLoading();
                erros = '';
                $.each(retorno.responseJSON, function (index, value) {
                    if (erros.length) {
                        erros += '<br>';
                    }
                    erros += value;
                });
                erros = erros.replace('conteudo ','');
                swal({
                    html:true,
                    title: "Oops",
                    text: erros,
                    type: 'error'
                });
            });
        }

        function removeAnexo(id){
            swal({
                        title: "Deseja realmente remover este anexo?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, remover!",
                        cancelButtonText: "Não",
                        closeOnConfirm: false
                    },
                    function(){
                        startLoading();
                        $.ajax("{{ url('/ordens-de-compra/remover-anexo') }}/"+ id, {}
                        ).done(function (retorno) {
                            stopLoading();
                            if(retorno.success){
                                swal('Removido','', 'success');
                                $('#anexo_'+id).remove();
                            }else{
                                swal('Oops',retorno.error, 'error');
                            }
                        }).fail(function (retorno) {
                            stopLoading();
                            erros = '';
                            $.each(retorno.responseJSON, function (index, value) {
                                if (erros.length) {
                                    erros += '<br>';
                                }
                                erros += value;
                            });
                            erros = erros.replace('conteudo ','');
                            swal({
                                html:true,
                                title: "Oops",
                                text: erros,
                                type: 'error'
                            });
                        });
                    });

        }

        $(function () {


            $(".formAnexos").submit(function (ev) {
                var formData = new FormData(this);
                ev.preventDefault();
                startLoading();
                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function() {  // Custom XMLHttpRequest
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                            myXhr.upload.addEventListener('progress', function () {
                                /* faz alguma coisa durante o progresso do upload */
                            }, false);
                        }
                        return myXhr;
                    }
                }).done(function (data) {
                    stopLoading();
                    item_id = ev.target.elements["item_id"].value;
                    $(ev.target)[0].reset();
                    $('#item_id_'+item_id).val(item_id);
                    $('#label_btn_anexo_'+item_id).html("<span>Selecionar</span>");
                    if(data.success){
                        swal('Anexos Enviados!',data.message, 'success');
                        $('#anexados_'+item_id).html('');
                        $.each(data.anexos, function (index, valor) {
                            $('#anexados_'+item_id).append('<div id="anexo_'+valor.id+'">'+
                                    '<span class="col-md-10">'+
                                    '<a href="'+valor.arquivo+'" class="btn btn-xs btn-flat btn-block btn-default" download="" target="_blank">'+
                                    '<i class="fa fa-download"></i> <span>'+valor.arquivo_nome+'</span>'+
                                    '</a>'+
                                    '</span>'+
                                    '<span class="col-md-2">'+
                                            '<button type="button" onclick="removeAnexo('+valor.id+');" class="btn btn-xs btn-flat btn-block btn-danger" target="_blank">'+
                                            '<i class="fa fa-trash"></i>'+
                                            '</button>'+
                                            '</span>'+
                                    '</div>');
                        });
                    }else{
                        swal('Oops',data.error, 'error');
                    }
                }).fail(function(){
                    stopLoading();
                });
            });

            $(".ck_emergencial").on("ifChanged",function(event) {
                alteraItem( $(event.target).attr('item_id'), 'emergencial', (event.target.checked?1:0) );
            });
        });

        function fechaOC() {
            swal({
                        title: "Finalizar esta Ordem de Compra?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#7ed321",
                        confirmButtonText: "Sim, já verifiquei tudo!",
                        cancelButtonText: "Não, ainda quero validar algo.",
                        closeOnConfirm: false
                    },
                    function() {
                        document.location = '{{ url('/ordens-de-compra/fechar-carrinho') }}';

                    });
        }
//        const app = new Vue({
//            el: '#app'
//        });
    </script>
@endsection
