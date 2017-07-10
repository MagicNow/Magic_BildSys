@extends('layouts.front')

@section('content')
    <style type="text/css">
        .fa-th-large {
            cursor: ns-resize;
        }

        .table-bordered td {
            border: 1px solid #5a5555 !important;
        }
    </style>
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <span class="pull-left title">
                   <h3>
                       <button type="button" class="btn btn-link" onclick="history.go(-1);">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                       </button>
                       <span>Criar previsão de memória de cálculo</span>
                   </h3>
                </span>
            </div>
        </div>
    </section>

    @php $count = 0; @endphp

    {!! Form::model($contrato, ['route' => ['contratos.memoria_de_calculo_salvar']]) !!}
    <div class="content">
        <div class="clearfix"></div>

        <input type="hidden" name="insumo_id" value="{{$insumo->id}}">
        <input type="hidden" name="unidade_sigla" value="{{$insumo->unidade_sigla}}">
        <input type="hidden" name="contrato_item_apropriacao_id" value="{{$contrato_item_apropriacao->id}}">
        <input type="hidden" name="contrato_item_id" value="{{$contrato_item_apropriacao->contrato_item_id}}">

        <div class="form-group col-md-2">
            {!! Form::label('contrato', 'Contrato:') !!}
            <p class="form-control">{!! $contrato->id !!}</p>
        </div>

        <div class="form-group col-md-4">
            {!! Form::label('fornecedor', 'Fornecedor:') !!}
            <p class="form-control">{!! $contrato->fornecedor->nome !!}</p>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('insumo', 'Insumo:') !!}
            <p class="form-control">{!! $contrato_item_apropriacao->codigo_insumo . ' - ' . $insumo->nome . ' - ' . $insumo->unidade_sigla!!}</p>
        </div>

        @if(count($previsoes))
            @php $previsao = $previsoes->first(); @endphp
            <div class="form-group col-md-3">
                {!! Form::label('planejamento_id', 'Tarefa:') !!}
                <p class="form-control">{{$previsao->planejamento->tarefa}}</p>
                <input type="hidden" name="planejamento_id" value="{{$previsao->planejamento->id}}">
            </div>

            <div class="form-group col-md-3">
                {!! Form::label('obra_torre_id', 'Torres:') !!}
                <p class="form-control">{{$previsao->obraTorre->nome}}</p>
                <input type="hidden" name="obra_torre_id" value="{{$previsao->obraTorre->id}}">
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('memoria_de_calculo', 'Memória de cálculo:') !!}
                @php
                    $modo = $previsao->memoriaCalculoBloco->memoriaCalculo->modo;

                    if($modo == 'C') {
                        $modo = 'Cartela';
                    } else if($modo == 'U') {
                        $modo = 'Unidade';
                    } else {
                        $modo = 'Torre';
                    }
                @endphp
                <p class="form-control">{{$previsao->memoriaCalculoBloco->memoriaCalculo->nome . ' - ' . $modo}}</p>
                <input type="hidden" name="memoria_de_calculo" value="{{$previsao->memoriaCalculoBloco->memoriaCalculo->id}}">
            </div>
        @else
            <div class="form-group col-md-6">
                {!! Form::label('memoria_de_calculo', 'Memória de cálculo:') !!}
                <a href="/memoriaCalculos/create"
                   class="btn btn-flat btn-sm btn-primary pull-right"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="Criar memória de cálculo"
                   style="margin-top: -10px;">
                    <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                </a>
                {!! Form::select('memoria_de_calculo', $memoria_de_calculo, \Illuminate\Support\Facades\Input::get('memoria_de_calculo') ? : null, ['class' => 'form-control select2', 'required' => 'required', 'onchange' => 'buscarMemoriaDeCalculo(this.value);']) !!}
            </div>
            @if(isset($memoriaCalculo))
                <div class="form-group col-md-3">
                    {!! Form::label('planejamento_id', 'Tarefa:') !!}
                    {!! Form::select('planejamento_id', $tarefas, null, ['class' => 'form-control select2', 'required' => 'required']) !!}
                </div>

                <div class="form-group col-md-3">
                    {!! Form::label('obra_torre_id', 'Torres:') !!}
                    {!! Form::select('obra_torre_id', $obra_torres, null, ['class' => 'form-control select2', 'required' => 'required']) !!}
                </div>
            @endif
        @endif

        @if(isset($memoriaCalculo))
            {{--Monta a estrutura de blocos igual a de ediçao--}}
            <div class="col-md-6" hidden>
                <ul class="list-group" id="blocos">
                    @if(isset($memoriaCalculo))
                        <?php
                        $countTrechos = 0;
                        if ($memoriaCalculo->modo == 'T') {
                            $nomeEstrutura = 'Estrutura';
                            $nomePavimento = 'Pavimento';
                            $nomeTrecho = 'Trecho';
                        } else {
                            $nomeEstrutura = 'Bloco';
                            $nomePavimento = 'Linha';
                            $nomeTrecho = 'Coluna';
                        }
                        ?>
                        @foreach($blocos as $indexBloco => $bloco)
                            <li class="list-group-item estruturaClass" bloco="{{ $indexBloco }}" id="bloco_list_item_{{ $indexBloco }}">
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-sm-8"><i class="fa fa-th-large"></i> &nbsp; {{ $nomeEstrutura }}:
                                        {!! Form::select('estrutura_bloco['. $indexBloco .']',
                                            \App\Models\NomeclaturaMapa::where('tipo',1)
                                            ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                            ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                            ->pluck('nome','id')->toArray() ,
                                            $bloco['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'estrutura_bloco_'.$indexBloco] ) !!}
                                        {!! Form::hidden('estrutura_bloco_ordem['. $indexBloco .']',$bloco['ordem']) !!}
                                    </div>
                                    <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                        <button type="button" onclick="adicionaPavimento({{ $indexBloco }})" class="btn btn-flat btn-xs btn-info"><i
                                                    class="fa fa-plus" ></i> {{ $nomePavimento }}
                                        </button>
                                        <button type="button" onclick="removeBloco({{ $indexBloco }})" title="Remover" class="btn btn-flat btn-xs btn-danger">
                                            <i class="fa fa-times" ></i></button>
                                    </div>
                                </div>
                                <div style="clear: both">
                                    <ul class="list-group pavBlocos" id="pavimentos_{{ $indexBloco }}">
                                        @if( count($bloco['itens']) )
                                            @foreach($bloco['itens'] as $indexPavimento => $pavimento)
                                                <li class="list-group-item pavimentosClass{{ $indexBloco }}" pavimento="{{ $indexPavimento }}" id="linha_{{ $indexBloco }}_{{ $indexPavimento }}">
                                                    <div class="row" style="margin-bottom: 10px">
                                                        <div class="col-sm-8">
                                                            <b class="fa fa-th-large"></b> &nbsp; {{ $nomePavimento }}:
                                                            {!! Form::select('pavimentos['. $indexBloco .']['.$indexPavimento.']',
                                                                \App\Models\NomeclaturaMapa::where('tipo',2)
                                                                ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                                                ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                                                ->pluck('nome','id')->toArray() ,
                                                                $pavimento['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'pavimentos_'.$indexBloco .'_'. $indexPavimento ] ) !!}
                                                            {!! Form::hidden('pavimento_bloco_ordem['.$indexBloco.']['.$indexPavimento.']',$pavimento['ordem']) !!}
                                                        </div>
                                                        <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                                            <button type="button" onclick="adicionaTrecho({{ $indexBloco }},{{ $indexPavimento }})"
                                                                    class="btn btn-flat btn-xs btn-warning"><i class="fa fa-plus"></i> {{ $nomeTrecho }}
                                                            </button>
                                                            <button type="button" onclick="removeLinha({{ $indexBloco }},{{ $indexPavimento }})" title="Remover"
                                                                    class="btn btn-flat btn-xs btn-danger"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <div style="clear: both">
                                                        <ul class="list-group trechoBlocos" id="trechos_{{ $indexBloco }}_{{ $indexPavimento }}">
                                                            @if( count($pavimento['itens']) )
                                                                @foreach($pavimento['itens'] as $indexTrecho => $trecho)
                                                                    <?php
                                                                    $countTrechos++;
                                                                    ?>
                                                                    <li class="list-group-item trechoClass{{ $indexBloco }}_{{ $indexPavimento }}" trecho="{{ $indexTrecho }}"
                                                                        id="blocoTrecho_{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">
                                                                        <div class="input-group">
                                                                            <strong class="input-group-addon"
                                                                                    id="trecho{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">{{ $nomeTrecho }}
                                                                            </strong>
                                                                            {!! Form::select('trecho['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',
                                                                                \App\Models\NomeclaturaMapa::where('tipo',3)
                                                                                ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                                                                ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                                                                ->pluck('nome','id')->toArray() ,
                                                                                $trecho['objId'],
                                                                                ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'trecho_' .$indexBloco .'_'. $indexPavimento . '_'. $indexTrecho] ) !!}
                                                                            {!! Form::hidden('trecho_id['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',$trecho['blocoId']) !!}

                                                                            <input type="hidden" name="trecho_bloco_ordem{{ '['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']' }}"
                                                                                   id="trecho_bloco_ordem_{{ $indexBloco.'_'.$indexPavimento.'_'.$indexTrecho }}" value="">
                                                                        <span class="input-group-btn">
                                                                            <button
                                                                                    type="button" onclick="removeTrecho({{ $indexBloco.','.$indexPavimento.','.$indexTrecho }})" title="Remover"
                                                                                    class="btn btn-flat btn-xs btn-danger"><i
                                                                                        class="fa fa-times"></i> </button>
                                                                        </span>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {{--Renderiza os blocos--}}
            <div class="col-md-12" id="visual"></div>

            <div class="col-md-12">
                <h3>
                    Filtros
                </h3>
                <div class="row form-group col-md-5">
                    <div class="row col-md-3">
                        {!! Form::label('filtro_estrutura', 'Estrutura:') !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::select('filtro_estrutura', $filtro_estruturas, null, ['class' => 'form-control select2', 'onchange' => 'filtrarEstrututa(this.value);']) !!}
                    </div>
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('filtro_preenchido', 'Preenchido:') !!}
                    {!! Form::checkbox('filtro_preenchido', null, false) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('filtro_nao_preenchido', 'Não preenchido:') !!}
                    {!! Form::checkbox('filtro_nao_preenchido', null, false) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('filtro_todos', 'Todos:') !!}
                    {!! Form::checkbox('filtro_todos', null, false) !!}
                </div>
            </div>

            <table class="table table-striped table-no-margin">
                <thead>
                <tr>
                    <th>Estrutura</th>
                    <th>Pavimento</th>
                    <th>Trecho</th>
                    <th style="width: 15%;">Data</th>
                    <th style="width: 15%;">Qtde</th>
                    <th style="width: 15%;">%</th>
                    <th style="width: 4%;"></th>
                </tr>
                </thead>
                <tbody id="tbody_previsoes">

                @if(count($previsoes))
                    @foreach($previsoes as $item)
                        @php $count = $item->id; @endphp
                        <tr id="linha_{{$item->id}}" memoria_calculo_bloco_id="{{$item->memoria_calculo_bloco_id}}" class="estrutura preenchido" estrutura="{{$item->memoriaCalculoBloco->estruturaObj->id}}">
                            <input type="hidden" name="itens[{{$item->id}}][memoria_calculo_bloco_id]" value="{{$item->memoria_calculo_bloco_id}}">
                            <input type="hidden" name="itens[{{$item->id}}][id]" value="{{$item->id}}">
                            <td>
                                {{$item->memoriaCalculoBloco->estruturaObj->nome}}
                            </td>
                            <td>
                                {{$item->memoriaCalculoBloco->pavimentoObj->nome}}
                            </td>
                            <td>
                                {{$item->memoriaCalculoBloco->trechoObj->nome}}
                            </td>
                            <td>
                                <input type="date" class="form-control" name="itens[{{$item->id}}][data_competencia]" value="{{$item->data_competencia->format('Y-m-d')}}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control money" name="itens[{{$item->id}}][qtd]" id="quantidade_{{$item->id}}" onkeyup="calcularPorcentagem(this.value, '{{$item->id}}');" value="{{number_format($item->qtd, 2, ',', '.')}}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control money" id="porcentagem_{{$item->id}}" onkeyup="calcularQuantidade(this.value, '{{$item->id}}');">
                            </td>
                            <td>
                                <button onclick="excluirLinha({{$item->id}});" class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Excluir" type="button">
                                    <i class="fa fa-remove fa-fw" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        @endif

        <div class="col-sm-12" style="margin-top: 10px;">
            <button class="btn btn-success pull-right flat" type="submit">
                <i class="fa fa-save"></i> Salvar
            </button>

            <button class="btn btn-default flat" onclick="history.go(-1);">
                <i class="fa fa-times"></i> Cancelar
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    var count = '{{$count}}';
    var qtd_item_apropriacao = '{{$contrato_item_apropriacao->qtd}}';
    var array_blocos_previstos = [];

    var estruturasObjs = [];

    $(function() {
        @if(isset($memoriaCalculo))
            atualizaVisual();
        @endif

        @if(count($previsoes))
            @foreach($previsoes as $item)
                array_blocos_previstos.push('{{$item->memoria_calculo_bloco_id}}');
                calcularPorcentagem('{{number_format($item->qtd, 2, ',', '.')}}', '{{$item->id}}');
            @endforeach
        @endif

        // Filtro de preenchido
        $('#filtro_preenchido').on('ifChanged', function (event) {
            $('.nao-preenchido').hide();
            $('.preenchido').show();
        });
    });

    // Função para adicionar linha na tabela
    function adicionarNaTabela(memoria_calculo_bloco_id, estrutura, pavimento, trecho, estrutura_id) {
        count ++;

        if($.inArray(memoria_calculo_bloco_id.toString(), array_blocos_previstos) !== -1) {
            $('[memoria_calculo_bloco_id='+memoria_calculo_bloco_id+']').css('background-color', '#f98d00');

            setTimeout(function(){
                $('[memoria_calculo_bloco_id='+memoria_calculo_bloco_id+']').animate({
                    backgroundColor: 'tranparent'
                }, 'slow');
            },3000);
        } else {
            $('#tbody_previsoes').append('\
                <tr id="linha_'+count+'"  class="estrutura nao-preenchido" estrutura="'+estrutura_id+'" memoria_calculo_bloco_id='+memoria_calculo_bloco_id+'>\
                    <input type="hidden" name="itens['+count+'][memoria_calculo_bloco_id]" value="'+memoria_calculo_bloco_id+'">\
                    <td>\
                        '+estrutura+'\
                    </td>\
                    <td>\
                        '+pavimento+'\
                    </td>\
                    <td>\
                        '+trecho+'\
                    </td>\
                    <td>\
                    <input type="date" class="form-control" name="itens['+count+'][data_competencia]" required>\
                    </td>\
                    <td>\
                        <input type="text" class="form-control money" name="itens['+count+'][qtd]" id="quantidade_'+count+'" onkeyup="calcularPorcentagem(this.value, '+count+');" required>\
                    </td>\
                    <td>\
                        <input type="text" class="form-control money" id="porcentagem_'+count+'" onkeyup="calcularQuantidade(this.value, '+count+');">\
                    </td>\
                    <td>\
                        <button onclick="removerLinha('+count+');" class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Remover" type="button">\
                            <i class="fa fa-remove fa-fw" aria-hidden="true"></i>\
                        </button>\
                    </td>\
                </tr>\
            ');
            recarregarMascara();
            array_blocos_previstos.push(memoria_calculo_bloco_id.toString());
        }
    }

    // Função para remover linha da tabela
    function removerLinha(id) {
        $('#linha_'+id).remove();
    }

    // Função para excluir a linha do banco e da tabela
    function excluirLinha(id) {
        swal({
            title: "Você tem certeza?",
            text: "Você não poderá mais recuperar este registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sim, Remover",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: '{{route('contratos.memoria_de_calculo.excluir_previsao')}}',
                type: 'POST',
                data: {'id' : id}
            }).done(function() {
                removerLinha(id);
            });
        });
    }

    // Interação entre quantidade e porcentagem.
    function calcularPorcentagem(qtd, id) {
        porcentagem =  (moneyToFloat(qtd) / qtd_item_apropriacao) * 100;

        if(porcentagem.toString().length <= 2) {
            porcentagem = porcentagem+',00'
        } else {
            porcentagem = porcentagem.toFixed(2)
        }

        $('#porcentagem_'+id).val(porcentagem.toString().replace('.', ','));

        recarregarMascara();
    }

    // Interação entre porcentagem e quantidade.
    function calcularQuantidade(porcentagem, id) {
        quantidade = qtd_item_apropriacao * (moneyToFloat(porcentagem) / 100);

        if(quantidade.toString().length <= 2) {
            quantidade = quantidade+',00'
        } else {
            quantidade = quantidade.toFixed(2)
        }

        $('#quantidade_'+id).val(quantidade.toString().replace('.', ','));

        recarregarMascara();
    }

    // Filtro de estruturas
    function filtrarEstrututa(valor) {
        if(valor){
            $(".estrutura").hide();
            $('[estrutura='+valor+']').show();
        } else {
            setTimeout(function () {
                $(".estrutura").show();
            }, 500);
        }
    }

    function buscarMemoriaDeCalculo(memoria_de_calculo_id) {
        startLoading();
        history.pushState("", document.title, location.pathname+'?memoria_de_calculo='+memoria_de_calculo_id);
        location.reload();
    }

    function atualizaVisual() {
        startLoading();
        arrayVisual = [];
        $('.estruturaClass').each(function (idx) {
            k = $(this).attr('bloco');
            $('#estrutura_bloco_ordem_' + k).val(idx);

            nome = $("#estrutura_bloco_" + k + " option:selected").text();

            idx = $(this).parent().children().index(this);
            item = {
                id: idx,
                nome: nome,
                objId: $("#estrutura_bloco_" + k + " option:selected").val(),
                itens: [],
                ordem: idx
            };
            arrayPavimentosOrdenada = [];
            $('.pavimentosClass'+k).each(function (idxPav) {

                idxPav2 = $(this).parent().children().index(this);
                p = $(this).attr('pavimento');

                $('#pavimento_bloco_ordem_' + k + '_' + p).val(idxPav);
                nomePav = $("#pavimentos_" + k + "_" + p + " option:selected").text();

                pavimentoItem = {
                    id: idxPav,
                    nome: nomePav,
                    objId: $("#pavimentos_" + k + "_" + p + " option:selected").val(),
                    itens: [],
                    ordem: idxPav
                }

                arrayTrechos = [];
                $('.trechoClass' + k + '_' + p).each(function (idxTrecho) {

                    idxTrecho = $(this).parent().children().index(this);

                    t = $(this).attr('trecho');

                    nomeTrech = $("#trecho_" + k + "_" + p + "_" + t + " option:selected").text();

                    $('#trecho_bloco_ordem_' + k + '_' + p + '_' + t).val(idxTrecho);

                    blocoId = $('input[name="trecho_id['+ k +']['+ p +']['+ t +']"]').val();

                    trechoItem = {
                        id: idxTrecho,
                        nome: nomeTrech,
                        objId: $("#trecho_" + k + "_" + p + "_" + t + " option:selected").val(),
                        ordem: idxTrecho,
                        blocoId: blocoId
                    }

                    arrayTrechos[idxTrecho] = trechoItem;

                });

                pavimentoItem.itens = arrayTrechos;

                arrayPavimentosOrdenada[idxPav] = pavimentoItem;
            });
            item.itens = arrayPavimentosOrdenada;

            arrayVisual[idx] = item;
        });


        // -- Preenche o visual
        visualHTML = '';
        arrayVisual.forEach(function (item, index) {
            objDBEstrutura = estruturasObjs[item.objId];
            largura = 100;
            if (objDBEstrutura != undefined) {
                largura = objDBEstrutura.largura_visual;
            }
            pavimentosDestaEstrutura = '';
            if (item.itens.length == 0) {
                pavimentosDestaEstrutura = '   <tr>' +
                        '       <td colspan="2"> &nbsp; </td>' +
                        '   </tr>';
            } else {
                item.itens.forEach(function (linhaPavimento, indicePav) {
                    objDBEstruturaPav = estruturasObjs[linhaPavimento.objId];
                    larguraPav = 100;
                    if (objDBEstruturaPav != undefined) {
                        larguraPav = objDBEstruturaPav.largura_visual;
                    }
                    trechosDestePavimento = '';
                    if (linhaPavimento.itens.length == 0) {
                        trechosDestePavimento = '<table  style="width: ' + larguraPav + '%; margin:0px auto; min-height: 31px;"><tr> <td> &nbsp;</td> </tr></table>';
                    } else {
                        trechosTD = '';
                        linhaPavimento.itens.forEach(function (trechoPav, indiceTrec) {
                            objDBEstruturaTrecho = estruturasObjs[trechoPav.objId];
                            larguraTrecho = 100;
                            if (objDBEstruturaTrecho != undefined) {
                                larguraTrecho = objDBEstruturaTrecho.largura_visual;
                            }

                            previsao_bloco_id = trechoPav.blocoId;
                            previsao_estrutura = "'"+item.nome+"'";
                            previsao_pavimento =  "'"+linhaPavimento.nome+ "'";
                            previsao_trecho =  "'"+trechoPav.nome+ "'";
                            previsao_estrutura_id = item.objId;

                            trechosTD += '<td onclick="adicionarNaTabela('+previsao_bloco_id+','+previsao_estrutura+','+previsao_pavimento+','+previsao_trecho+','+previsao_estrutura_id+')" style="cursor:pointer;">&nbsp;' + trechoPav.nome + '&nbsp;</td>';
                        });
                        trechosDestePavimento = '<table class="table-bordered" style="width: ' + larguraPav + '%; margin:0px auto;min-height: 31px;"><tr> ' + trechosTD + ' </tr></table>';
                    }

                    pavimentosDestaEstrutura += '   <tr><td class="warning" width="15%">' + linhaPavimento.nome + '</td> ' +
                            ' <td style="padding: 0px !important;"> ' + trechosDestePavimento + '</td> ' +
                            ' </tr>';
                });
            }

            visualHTML += '<div class="row">' +
                    '<div class="col-sm-12 text-left">' + item.nome + '</div> ' +
                    '<div class="col-sm-12"> ' +
                    ' <table class="table table-condensed" style="width: ' + largura + '%; margin:5px auto;"> ' +
                    pavimentosDestaEstrutura +
                    ' </table> ' +
                    '</div> ' +
                    '</div>';
        });
        $('#visual').html(visualHTML);
        stopLoading();
    }

    function recarregarMascara() {
        $('.money').maskMoney({
            allowNegative: false,
            thousands: '.',
            decimal: ','
        });
    }
</script>
@endsection