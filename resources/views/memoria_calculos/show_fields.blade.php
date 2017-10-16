<style type="text/css">
    .fa-th-large {
        cursor: ns-resize;
    }

    .table-bordered td {
        border: 1px solid #5a5555 !important;
    }
</style>

<!-- Nome Field -->
<div class="form-group col-sm-3">
    {!! Form::label('obra_id', 'Obra:') !!}
    <div class="form-control">
        {{ $memoriaCalculo->obra->nome }}
    </div>
</div>
<!-- Nome Field -->
<div class="form-group col-sm-5">
    {!! Form::label('nome', 'Nome:') !!}
    <div class="form-control">
        {{ $memoriaCalculo->nome }}
    </div>
</div>

<!-- Modo Field -->
<div class="form-group col-sm-2">
    {!! Form::label('modo', 'Modo:') !!}
    <div class="form-control">
        <?php
            $modos = ['T'=>'Torre','C'=>'Cartela','U'=>'Unidade'];
        ?>
        {{ $modos[$memoriaCalculo->modo] }}
    </div>
</div>

<!-- Padrão Field -->
<div class="form-group col-sm-2">
    {!! Form::label('padrao', 'Padrão:') !!}
    <div class="form-control">
        {{ $memoriaCalculo->padrao?'SIM':'NÃO' }}
    </div>

</div>
<div>
    <div class="col-md-12" style="opacity: 0" id="blocosEdit">
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
                    <li class="list-group-item estruturaClass" bloco="{{ $indexBloco }}"
                        id="bloco_list_item_{{ $indexBloco }}">
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-sm-8"><i class="fa fa-th-large"></i> &nbsp; {{ $nomeEstrutura }}:
                                @if($bloco['editavel'])
                                    {!! Form::select('estrutura_bloco['. $indexBloco .']',
                                        \App\Models\NomeclaturaMapa::where('tipo',1)
                                        ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                        ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
                                        ->pluck('nome','id')->toArray() ,
                                        $bloco['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'estrutura_bloco_'.$indexBloco] ) !!}
                                @else
                                    {!! Form::hidden('estrutura_bloco['. $indexBloco .']', $bloco['objId'], ['id'=>'estrutura_bloco_'.$indexBloco, 'nome'=>$bloco['nome']]) !!}
                                    <span class="form-control"
                                          title="Não é possível alterar pois já existem previsões amarradas"
                                          data-toggle="tooltip" data-placement="top">{{ $bloco['nome'] }}</span>
                                @endif
                                {!! Form::hidden('estrutura_bloco_ordem['. $indexBloco .']',$bloco['ordem']) !!}
                            </div>
                            <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                <button type="button" onclick="adicionaPavimento({{ $indexBloco }})"
                                        class="btn btn-flat btn-xs btn-info"><i
                                            class="fa fa-plus"></i> {{ $nomePavimento }}
                                </button>
                                @if($bloco['editavel'])
                                    <button type="button" onclick="removeBloco({{ $indexBloco }})" title="Remover"
                                            class="btn btn-flat btn-xs btn-danger">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div style="clear: both">
                            <ul class="list-group pavBlocos" id="pavimentos_{{ $indexBloco }}">
                                @if( count($bloco['itens']) )
                                    @foreach($bloco['itens'] as $indexPavimento => $pavimento)
                                        <li class="list-group-item pavimentosClass{{ $indexBloco }}"
                                            pavimento="{{ $indexPavimento }}"
                                            id="linha_{{ $indexBloco }}_{{ $indexPavimento }}">
                                            <div class="row" style="margin-bottom: 10px">
                                                <div class="col-sm-8">
                                                    <b class="fa fa-th-large"></b> &nbsp; {{ $nomePavimento }}:
                                                    @if($pavimento['editavel'])
                                                        {!! Form::select('pavimentos['. $indexBloco .']['.$indexPavimento.']',
                                                            \App\Models\NomeclaturaMapa::where('tipo',2)
                                                            ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                                            ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
                                                            ->pluck('nome','id')->toArray() ,
                                                            $pavimento['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'pavimentos_'.$indexBloco .'_'. $indexPavimento ] ) !!}
                                                    @else
                                                        {!! Form::hidden('pavimentos['. $indexBloco .']['.$indexPavimento.']',
                                                        $pavimento['objId'], ['id'=>'pavimentos_'.$indexBloco .'_'. $indexPavimento, 'nome'=>$pavimento['nome']]) !!}
                                                        <span class="form-control"
                                                              title="Não é possível alterar pois já existem previsões amarradas"
                                                              data-toggle="tooltip"
                                                              data-placement="top">{{ $pavimento['nome'] }}</span>
                                                    @endif
                                                    {!! Form::hidden('pavimento_bloco_ordem['.$indexBloco.']['.$indexPavimento.']',$pavimento['ordem']) !!}
                                                </div>
                                                <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                                    <button type="button"
                                                            onclick="adicionaTrecho({{ $indexBloco }},{{ $indexPavimento }})"
                                                            class="btn btn-flat btn-xs btn-warning"><i
                                                                class="fa fa-plus"></i> {{ $nomeTrecho }}
                                                    </button>
                                                    @if($pavimento['editavel'])
                                                        <button type="button"
                                                                onclick="removeLinha({{ $indexBloco }},{{ $indexPavimento }})"
                                                                title="Remover"
                                                                class="btn btn-flat btn-xs btn-danger">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div style="clear: both">
                                                <ul class="list-group trechoBlocos"
                                                    id="trechos_{{ $indexBloco }}_{{ $indexPavimento }}">
                                                    @if( count($pavimento['itens']) )
                                                        @foreach($pavimento['itens'] as $indexTrecho => $trecho)
                                                            <?php
                                                            $countTrechos++;
                                                            ?>
                                                            <li class="list-group-item trechoClass{{ $indexBloco }}_{{ $indexPavimento }}"
                                                                trecho="{{ $indexTrecho }}"
                                                                id="blocoTrecho_{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">
                                                                <div class="input-group">
                                                                    <strong class="input-group-addon"
                                                                            id="trecho{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">{{ $nomeTrecho }}
                                                                    </strong>
                                                                    @if($trecho['editavel'])
                                                                        {!! Form::select('trecho['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',
                                                                            \App\Models\NomeclaturaMapa::where('tipo',3)
                                                                            ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                                                            ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
                                                                            ->pluck('nome','id')->toArray() ,
                                                                            $trecho['objId'],
                                                                            ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'trecho_' .$indexBloco .'_'. $indexPavimento . '_'. $indexTrecho] ) !!}
                                                                    @else
                                                                        {!! Form::hidden('trecho['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',
                                                                        $trecho['objId'], ['id'=>'trecho_' .$indexBloco .'_'. $indexPavimento . '_'. $indexTrecho, 'nome'=>$trecho['nome']]) !!}
                                                                        <span class="form-control"
                                                                              title="Não é possível alterar pois já existem previsões amarradas"
                                                                              data-toggle="tooltip"
                                                                              data-placement="top">{{ $trecho['nome'] }}</span>
                                                                    @endif
                                                                    @if(!isset($clonando))
                                                                        {!! Form::hidden('trecho_id['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',$trecho['blocoId']) !!}
                                                                    @endif

                                                                    <input type="hidden"
                                                                           name="trecho_bloco_ordem{{ '['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']' }}"
                                                                           id="trecho_bloco_ordem_{{ $indexBloco.'_'.$indexPavimento.'_'.$indexTrecho }}"
                                                                           value="{{ $trecho['ordem'] }}">
                                                                    @if($trecho['editavel'])
                                                                        <span class="input-group-btn">
                                                                                <button
                                                                                        type="button"
                                                                                        onclick="removeTrecho({{ $indexBloco.','.$indexPavimento.','.$indexTrecho }})"
                                                                                        title="Remover"
                                                                                        class="btn btn-flat btn-xs btn-danger"><i
                                                                                            class="fa fa-times"></i> </button>
                                                                            </span>
                                                                    @endif
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


    <div class="col-md-12" id="visual">

    </div>
</div>


@section('scripts')
    <script type="text/javascript">
        var estruturasObjs = [];
        var pavimentosObjs = [];
        var trechosObjs = [];

        var estruturas = '';
        var pavimentos = '';
        var trechos = '';
        var nomeEstrutura = '';
        var nomePavimento = '';
        var nomeTrecho = '';
        var blocos = 0;
        var pavimentosCount = 0;
        var trechosCount = 0;
        $(function () {
            // ready
            @if(isset($memoriaCalculo))
                buscaNomeclaturas('{{ $memoriaCalculo->modo }}');
            @else
                buscaNomeclaturas('T');
            @endif

            sortable('#blocos', {handle: 'i'});

            sortable('#blocos')[0].addEventListener('sortstop', function (e) {
                atualizaVisual();
            });

            @if(isset($memoriaCalculo))
                    blocos = {{ $indexBloco + 1 }};
            pavimentosCount = {{ $indexPavimento + 1 }};
            trechosCount = {{ $countTrechos + 1 }};

            sortable('.pavBlocos', {handle: 'b'});

            sortable('.pavBlocos')[0].addEventListener('sortstop', function (e) {
                atualizaVisual();
            });

            sortable('.trechoBlocos', {handle: 'strong'});

            sortable('.trechoBlocos')[0].addEventListener('sortstop', function (e) {
                atualizaVisual();
            });
            @endif

        });

        function atualizaVisual() {
            arrayVisual = [];
            $('.estruturaClass').each(function (idx) {
                k = $(this).attr('bloco');
                $('#estrutura_bloco_ordem_' + k).val(idx);
                if ($("#estrutura_bloco_" + k).is('select')) {
                    nome = $("#estrutura_bloco_" + k + " option:selected").text();
                    objID = $("#estrutura_bloco_" + k + " option:selected").val();
                } else {
                    nome = $("#estrutura_bloco_" + k).attr('nome');
                    objID = $("#estrutura_bloco_" + k).val();
                }


                idx = $(this).parent().children().index(this);

                item = {
                    id: idx,
                    nome: nome,
                    objId: objID,
                    itens: [],
                    ordem: idx
                };
                arrayPavimentosOrdenada = [];
                $('.pavimentosClass' + k).each(function (idxPav) {

                    idxPav2 = $(this).parent().children().index(this);
                    p = $(this).attr('pavimento');

                    $('#pavimento_bloco_ordem_' + k + '_' + p).val(idxPav);

                    if ($("#pavimentos_" + k + "_" + p).is('select')) {
                        nomePav = $("#pavimentos_" + k + "_" + p + " option:selected").text();
                        pavID = $("#pavimentos_" + k + "_" + p + " option:selected").val();
                    } else {
                        nomePav = $("#pavimentos_" + k + "_" + p).attr('nome');
                        pavID = $("#pavimentos_" + k + "_" + p).val();
                    }


                    pavimentoItem = {
                        id: idxPav,
                        nome: nomePav,
                        objId: pavID,
                        itens: [],
                        ordem: idxPav
                    }

                    arrayTrechos = [];
                    $('.trechoClass' + k + '_' + p).each(function (idxTrecho) {

                        idxTrecho = $(this).parent().children().index(this);

                        t = $(this).attr('trecho');

                        if ($("#trecho_" + k + "_" + p + "_" + t).is('select')) {
                            nomeTrech = $("#trecho_" + k + "_" + p + "_" + t + " option:selected").text();
                            trechID = $("#trecho_" + k + "_" + p + "_" + t + " option:selected").val();
                        } else {
                            nomeTrech = $("#trecho_" + k + "_" + p + "_" + t).attr('nome');
                            trechID = $("#trecho_" + k + "_" + p + "_" + t).val();
                        }


                        $('#trecho_bloco_ordem_' + k + '_' + p + '_' + t).val(idxTrecho);

                        trechoItem = {
                            id: idxTrecho,
                            nome: nomeTrech,
                            objId: trechID,
                            ordem: idxTrecho
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
                                trechosTD += '<td>&nbsp;' + trechoPav.nome + '&nbsp;</td>';
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
            $('#blocosEdit').hide();

        }

        function buscaNomeclaturas(valor) {
            if (valor == 'T') {
                nomeEstrutura = 'Estrutura';
                nomePavimento = 'Pavimento';
                nomeTrecho = 'Trecho';
            } else {
                nomeEstrutura = 'Bloco';
                nomePavimento = 'Linha';
                nomeTrecho = 'Coluna';
            }
            estruturasObjs = [];
            pavimentosObjs = [];
            trechosObjs = [];

            $('#btn_adicionar_bloco').attr('disabled', true);
            estruturas = '<option value="" selected="selected">Escolha</option>';
            pavimentos = '<option value="" selected="selected">Escolha</option>';
            trechos = '<option value="" selected="selected">Escolha</option>';
            $.ajax('{{ url("nomeclatura-mapas/json") }}?modo=' + valor)
                    .fail(function (retorno) {
                        swal({title: 'Erro na solicitação', type: 'error'}, function () {
                            document.location.reload();
                        });
                    })
                    .done(function (retorno) {
                        $.each(retorno, function (index, nomeclatura) {
                            if (nomeclatura.tipo == 1) {
                                estruturas += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                estruturasObjs[nomeclatura.id] = nomeclatura;
                            }
                            if (nomeclatura.tipo == 2) {
                                pavimentos += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                pavimentosObjs[nomeclatura.id] = nomeclatura;
                            }
                            if (nomeclatura.tipo == 3) {
                                trechos += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                trechosObjs[nomeclatura.id] = nomeclatura;
                            }
                        });
                        $('#btn_adicionar_bloco').attr('disabled', false);
                        atualizaVisual();
                    });
        }
    </script>
@stop