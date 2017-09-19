@extends('layouts.front')

@section('content')
    <style type="text/css">
        .fa-th-large {
            cursor: ns-resize;
        }

        .table-bordered td {
            border: 1px solid #5a5555 !important;
        }

        .trecho{
            cursor: pointer;
            border: 2px solid #f98d00 !important;
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

    {!! Form::model($contrato, ['route' => ['contratos.memoria_de_calculo_salvar'], 'id'=>'formPrevisao']) !!}
    <div class="content">
        <div class="clearfix"></div>

        <input type="hidden" name="insumo_id" value="{{$insumo->id}}">
        <input type="hidden" name="unidade_sigla" value="{{$insumo->unidade_sigla}}">
        <input type="hidden" name="contrato_item_apropriacao_id" value="{{$contrato_item_apropriacao->id}}">
        <input type="hidden" name="contrato_item_id" value="{{$contrato_item_apropriacao->contrato_item_id}}">

        <div class="modal-header" style="margin-bottom: 40px;">
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

            <div class="form-group col-md-3">
                {!! Form::label('qtd_insumo', 'Quantidade do insumo:') !!}
                <p class="form-control">{!! number_format($contrato_item_apropriacao->qtd, 2, ',', '.') . ' - ' . $insumo->unidade_sigla!!}</p>
            </div>

            <div class="form-group col-md-3">
                {!! Form::label('saldo_qtd_insumo', 'Saldo de quantidade do insumo:') !!}
                <p class="form-control">{!! number_format($contrato_item_apropriacao->qtd, 2, ',', '.') . ' - ' . $insumo->unidade_sigla!!}</p>
            </div>

            <div class="form-group col-md-3">
                {!! Form::label('qtd_distribuida', 'Quantidade distribuida:') !!}
                <p class="form-control" id="distribuida">0,00</p>
            </div>

            <div class="form-group col-md-3">
                {!! Form::label('qtd_distribuir', 'Quantidade à distribuir:') !!}
                <p class="form-control" id="a_distribuir">{{ number_format($contrato_item_apropriacao->qtd, 2, ',', '.') }}</p>
                <input type="hidden" id="qtd_a_distribuir" value="{{ $contrato_item_apropriacao->qtd }}">
            </div>
        </div>
        @if(count($previsoes))
            @php $previsao = $previsoes->first(); @endphp

            <div class="form-group col-md-6">
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
                {!! Form::label('obra_torre_id', 'Torres:') !!}
                {!! Form::select('obra_torre_id', $obra_torres, \Illuminate\Support\Facades\Input::get('torre') ? : null, ['class' => 'form-control select2', 'required' => 'required', 'onchange' => 'selecionaTorre(this.value)']) !!}
            </div>
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
                {!! Form::select('memoria_de_calculo', $memoria_de_calculo, \Illuminate\Support\Facades\Input::get('memoria_de_calculo') ? : null,
                 ['class' => 'form-control select2', 'required' => 'required', 'onchange' => 'buscarMemoriaDeCalculo(this.value);']) !!}
            </div>
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
                                            ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                            ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
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
                                                                ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                                                ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
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
                                                                                ->where('apenas_cartela',($memoriaCalculo->modo=='C'?'1':'0') )
                                                                                ->where('apenas_unidade',($memoriaCalculo->modo=='U'?'1':'0') )
                                                                                ->pluck('nome','id')->toArray() ,
                                                                                $trecho['objId'],
                                                                                ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'trecho_' .$indexBloco .'_'. $indexPavimento . '_'. $indexTrecho] ) !!}
                                                                            {!! Form::hidden('trecho_id['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',$trecho['blocoId'], ['class' => 'todosTrechos']) !!}

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
            <div class="col-md-6" id="visual"></div>

            <div class="col-md-6">

                <div class="form-group">
                    {!! Form::label('tarefa_referencia', 'Tarefa referência:') !!}
                    {!! Form::select('tarefa_referencia', $planejamentos, null, ['class' => 'form-control select2', 'id' => 'tarefa_referencia']) !!}
                </div>

                <div class="box box-primary" id="boxPreenchimento" style="display: none;">
                    <div class="box-header with-border">
                        Informe os Valores desta medição
                        <button onclick="cancelarEdicao();"
                                class="btn btn-flat btn-link btn-xs pull-right" data-toggle="tooltip"
                                data-placement="top" title="Fechar" type="button">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="box-body">
                        <input type="hidden" id="preenchimento_memoria_calculo_bloco_id" value="">
                        <div class="form-group">
                            <div class="form-control" id="preenchimento_texto">
                                Pré-Tipo - Subsolo 1 - Estacionamento
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('preenchimento_planejamento_id', 'Tarefa:') !!}
                            {!! Form::select('preenchimento_planejamento_id', $planejamentos, null, [
                                'id' => 'preenchimento_planejamento_id',
                                'class' => 'form-control select2', 'onchange'=>"atualizaReferencia('planejamento_id_',this.value);"]) !!}
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('preenchimento_data_competencia', 'Data Prevista:') !!}
                                    <input type="date" class="form-control" name="preenchimento_data_competencia"
                                           value=""  id="preenchimento_data_competencia"
                                        onchange="atualizaReferencia('data_',this.value);">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('preenchimento_qtd', 'Quantidade:') !!}
                                    <input type="text" class="form-control money text-right" name="quantidade_preenchimento"
                                           id="quantidade_preenchimento"
                                           onkeyup="atualizaQtd();"
                                           value="" >
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('porcentagem_0', 'Percentual:') !!}
                                    <input type="text" class="form-control money text-right"
                                           id="porcentagem_preenchimento"
                                           onkeyup="atualizaPercentual();">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h3>
                    Filtros
                </h3>
                <div class="form-group col-md-6">
                    {!! Form::label('filtro_estrutura', 'Estrutura:') !!}
                    {!! Form::select('filtro_estrutura', $filtro_estruturas, null, ['class' => 'form-control select2', 'onchange' => 'filtrarEstrututa(this.value);']) !!}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('filtro_preenchido', 'Preenchido:') !!}
                    <br>
                    {!! Form::checkbox('filtro_preenchido', null, false, ['onclick' => 'filtrarCheck();']) !!}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('filtro_nao_preenchido', 'Não preenchido:') !!}
                    <br>
                    {!! Form::checkbox('filtro_nao_preenchido', null, false, ['onclick' => 'filtrarCheck();']) !!}
                </div>
                <table class="table table-striped table-no-margin">
                    <thead>
                    <tr>
                        <th>Torre</th>
                        <th>Memória de cálculo</th>
                        <th>Estrutura - Pavimento - Trecho</th>
                        <th>Tarefa</th>
                        <th style="width: 10%;">Data</th>
                        <th style="width: 15%;">Qtde</th>
                        <th style="width: 10%;">%</th>
                        <th style="width: 4%;"></th>
                    </tr>
                    </thead>
                    <tbody id="tbody_previsoes">

                    @if(count($previsoes))
                        @foreach($previsoes as $item)
                            @php $count = $item->id; @endphp
                            <tr id="linha_{{$item->id}}" memoria_calculo_bloco_id="{{$item->memoria_calculo_bloco_id}}"
                                class="estrutura preenchido" estrutura="{{$item->memoriaCalculoBloco->estruturaObj->id}}">
                                <input type="hidden" name="itens[{{$item->id}}][memoria_calculo_bloco_id]" value="{{$item->memoria_calculo_bloco_id}}">
                                <input type="hidden" name="itens[{{$item->id}}][id]" value="{{$item->id}}">
                                <td>
                                    {{$item->memoriaCalculoBloco->estruturaObj->nome}}
                                    -
                                    {{$item->memoriaCalculoBloco->pavimentoObj->nome}}
                                    -
                                    {{$item->memoriaCalculoBloco->trechoObj->nome}}
                                </td>
                                <td>
                                    {!! Form::select('itens['.$item->id.'][planejamento_id]',
                                    $planejamentos, $item->planejamento->id, ['class' => 'form-control select2',
                                    'required', 'id'=>'planejamento_id_'.$item->memoria_calculo_bloco_id, 'onfocus'=>"cancelarEdicao()"]) !!}
                                </td>
                                <td>
                                    <input type="date" class="form-control" name="itens[{{$item->id}}][data_competencia]"
                                           value="{{$item->data_competencia->format('Y-m-d')}}" required  onfocus="cancelarEdicao();"
                                           id="data_{{$item->memoria_calculo_bloco_id}}" onkeyup="verificarPreenchido('{{$item->id}}');"
                                           onchange="verificarPreenchido('{{$item->id}}');">
                                </td>
                                <td>
                                    <input type="text" class="form-control money calc_quantidade" name="itens[{{$item->id}}][qtd]"
                                           id="quantidade_{{$item->memoria_calculo_bloco_id}}"
                                           onfocus="cancelarEdicao();"
                                           onkeyup="calcularPorcentagem(this.value, '{{$item->memoria_calculo_bloco_id}}');verificarPreenchido('{{$item->memoria_calculo_bloco_id}}');"
                                           value="{{number_format($item->qtd, 2, ',', '.')}}" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control money calc_porcentagem" value="{{number_format((($item->qtd/$contrato_item_apropriacao->qtd)*100), 2, ',', '.')}}"
                                           id="porcentagem_{{$item->memoria_calculo_bloco_id}}"  onfocus="cancelarEdicao();"
                                           onkeyup="calcularQuantidade(this.value, '{{$item->memoria_calculo_bloco_id}}');verificarPreenchido('{{$item->memoria_calculo_bloco_id}}');">
                                </td>
                                <td>
                                    <button onclick="excluirLinha({{$item->id}}, {{$item->memoria_calculo_bloco_id}});"
                                            class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip"
                                            data-placement="top" title="Excluir" type="button">
                                        <i class="fa fa-remove fa-fw" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12" style="margin-top: 20px;">
                <button class="btn btn-success pull-right flat" type="submit">
                    <i class="fa fa-save"></i> Salvar
                </button>

                <button class="btn btn-default flat" onclick="history.go(-1);">
                    <i class="fa fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    var count = '{{$count}}';
    var qtd_item_apropriacao = '{{$contrato_item_apropriacao->qtd}}';
    var array_blocos_previstos = [];
    var options_planejamento = [];
    var estruturasObjs = [];
    var quantidade_distribuida = 0;

    function cancelarEdicao(){
        $('#boxPreenchimento').hide('fast');
    }

    $(function() {
        @php
            $array_session_blocos = Session::get('previsao-de-memoria-de-calculo-'.$contrato->id.'-'.$contrato_item_apropriacao->id);
        @endphp

        @if(count($array_session_blocos))
            @foreach($array_session_blocos as $array_session)
                adicionarNaTabela(
                    '{{$array_session['memoria_calculo_bloco_id']}}',
                    '{{$array_session['estrutura']}}',
                    '{{$array_session['pavimento']}}',
                    '{{$array_session['trecho']}}',
                    '{{$array_session['estrutura_id']}}',
                    1,
                    '{{$array_session['torre']}}',
                    '{{$array_session['memoria_calculo']}}'
                );

                $('#data_{{$array_session['memoria_calculo_bloco_id']}}').val('{{$array_session['data']}}');
                $('#quantidade_{{$array_session['memoria_calculo_bloco_id']}}').val('{{$array_session['quantidade']}}');
                $('#planejamento_id_{{$array_session['memoria_calculo_bloco_id']}}').val('{{$array_session['planejamento_id']}}').trigger('change');

                if('{{$array_session['quantidade']}}') {
                    calcularPorcentagem('{{$array_session['quantidade']}}', '{{$array_session['memoria_calculo_bloco_id']}}');
                }

                array_blocos_previstos.push({{$array_session['memoria_calculo_bloco_id']}});
        
                setTimeout(function () {
                    $('#td_bloco_{{$array_session['memoria_calculo_bloco_id']}}').attr('style','border: 2px solid #f98d00 !important;');
                }, 1000);
            @endforeach
        @endif

        $('#formPrevisao').submit(function (evento) {
            evento.preventDefault();
            if($('#qtd_a_distribuir').val()>0){
               swal('Ainda resta quantidade à distribuir!','','error');
               return false;
            }else if($('#qtd_a_distribuir').val()<0){
               swal('Você distribuiu mais do que disponível!','Analise o cálculo de quantidades, e ajuste para' +
                       ' que no campo à distribuir fique exatamente 0(zero)','error');
               return false;
            }else{
               this.submit();
               return true;
            }
        });

        @if(isset($memoriaCalculo))
            buscaNomeclaturas('{{ $memoriaCalculo->modo }}');
            atualizaVisual();
        @else
            buscaNomeclaturas('T');
        @endif


        @if(count($previsoes))
            @foreach($previsoes as $item)
                array_blocos_previstos.push({{$item->memoria_calculo_bloco_id}});
                calcularPorcentagem('{{number_format($item->qtd, 2, ',', '.')}}', '{{$item->id}}');

                setTimeout(function () {
                    $('#td_bloco_{{$item->memoria_calculo_bloco_id}}').attr('style','border: 2px solid #f98d00 !important;');
                }, 1000);
            @endforeach
        @endif

        $('#filtro_preenchido').iCheck('destroy');
        $('#filtro_nao_preenchido').iCheck('destroy');
    });

    function abreEdicaoFacil(memoria_calculo_bloco_id, estrutura, pavimento, trecho) {
        $('#preenchimento_memoria_calculo_bloco_id').val(memoria_calculo_bloco_id);
        $('#preenchimento_texto').text(estrutura + ' - ' + pavimento + ' - ' + trecho);
        if($('#planejamento_id_'+memoria_calculo_bloco_id).length){
            $('#preenchimento_planejamento_id').val($('#planejamento_id_'+memoria_calculo_bloco_id).val()).trigger('change');
            $('#preenchimento_data_competencia').val($('#data_'+memoria_calculo_bloco_id).val());
            $('#quantidade_preenchimento').val($('#quantidade_'+memoria_calculo_bloco_id).val());
            $('#porcentagem_preenchimento').val($('#porcentagem_'+memoria_calculo_bloco_id).val());
        }

        $('#boxPreenchimento').show('fast');
    }

    function atualizaQtd(){
        atualizaReferencia('quantidade_',$('#quantidade_preenchimento').val());
        calcularPorcentagem($('#quantidade_preenchimento').val(), 'preenchimento');
        atualizaReferencia('porcentagem_',$('#porcentagem_preenchimento').val());
    }

    function atualizaPercentual(){
        atualizaReferencia('porcentagem_',$('#porcentagem_preenchimento').val());
        calcularQuantidade($('#porcentagem_preenchimento').val(), 'preenchimento');
        atualizaReferencia('quantidade_',$('#quantidade_preenchimento').val());
        atualizaQtd();
    }

    function atualizaReferencia(qual, valor) {
        alvo = $('#'+qual+$('#preenchimento_memoria_calculo_bloco_id').val());
        if(alvo.is('select')){
            alvo.val(valor).trigger('change');
        }else{
            alvo.val(valor);
        }
    }

    // Função para adicionar linha na tabela
    function adicionarNaTabela(memoria_calculo_bloco_id, estrutura, pavimento, trecho, estrutura_id, sessao, torre, memoria_calculo) {
        count ++;

        if($.inArray(memoria_calculo_bloco_id, array_blocos_previstos) !== -1) {
            $('[memoria_calculo_bloco_id=' + memoria_calculo_bloco_id + ']').css('background-color', '#f98d00')
                .animate({
                backgroundColor: 'tranparent'
                }, 'slow');
        } else {
            @foreach($planejamentos as $id => $nome)
                    options_planejamento += '<option value="{{$id}}">{{$nome}}</option>';
            @endforeach

            if(!sessao) {
                torre = $("#obra_torre_id option:selected").text();
                memoria_calculo = $("#memoria_de_calculo option:selected").text();

                putSessionMemoriaDeCalculo(memoria_calculo_bloco_id, estrutura, pavimento, trecho, estrutura_id, torre, memoria_calculo);
            }

            $('#tbody_previsoes').append('\
                <tr id="linha_'+count+'"  class="estrutura nao-preenchido" estrutura="'+estrutura_id+'" memoria_calculo_bloco_id='+memoria_calculo_bloco_id+'>\
                    <input type="hidden" name="itens['+count+'][memoria_calculo_bloco_id]" value="'+memoria_calculo_bloco_id+'">\
                    <td>\
                        '+torre+'\
                    </td>\
                    <td>\
                        '+memoria_calculo+'\
                    </td>\
                    <td>\
                        '+estrutura+' - \
                        '+pavimento+' - \
                        '+trecho+'\
                    </td>\
                    <td>\
                        <select class="form-control select2_add" name="itens['+count+
                            '][planejamento_id]" id="planejamento_id_'+memoria_calculo_bloco_id+'" required onchange="putSessionMemoriaDeCalculo('+memoria_calculo_bloco_id+','+"'"+estrutura+"'"+','+"'"+pavimento+"'"+','+"'"+trecho+"'"+','+estrutura_id+','+"'"+torre+"'"+','+"'"+memoria_calculo+"'"+');">\
                        ' + options_planejamento + '\
                        </select>\
                    </td>\
                    <td>\
                    <input type="date" class="form-control" name="itens['+count+'][data_competencia]" id="data_'+memoria_calculo_bloco_id+
                        '" required onkeyup="verificarPreenchido('+count+','+memoria_calculo_bloco_id+');putSessionMemoriaDeCalculo('+memoria_calculo_bloco_id+','+"'"+estrutura+"'"+','+"'"+pavimento+"'"+','+"'"+trecho+"'"+','+estrutura_id+','+"'"+torre+"'"+','+"'"+memoria_calculo+"'"+');"' +
                    ' onfocus="cancelarEdicao();"  onchange="verificarPreenchido('+count+','+memoria_calculo_bloco_id+');putSessionMemoriaDeCalculo('+memoria_calculo_bloco_id+','+"'"+estrutura+"'"+','+"'"+pavimento+"'"+','+"'"+trecho+"'"+','+estrutura_id+','+"'"+torre+"'"+','+"'"+memoria_calculo+"'"+');">\
                    </td>\
                    <td>\
                        <input type="text" class="form-control money calc_quantidade" name="itens['+count+'][qtd]" id="quantidade_'+memoria_calculo_bloco_id+
                        '" onkeyup="calcularPorcentagem(this.value, '+memoria_calculo_bloco_id+');verificarPreenchido('+count+','+memoria_calculo_bloco_id+');putSessionMemoriaDeCalculo('+memoria_calculo_bloco_id+','+"'"+estrutura+"'"+','+"'"+pavimento+"'"+','+"'"+trecho+"'"+','+estrutura_id+','+"'"+torre+"'"+','+"'"+memoria_calculo+"'"+');" \
                          onfocus="cancelarEdicao();" required>\
                    </td>\
                    <td>\
                        <input type="text" class="form-control money calc_porcentagem" id="porcentagem_'+memoria_calculo_bloco_id+
                        '" onkeyup="calcularQuantidade(this.value, '+memoria_calculo_bloco_id+');verificarPreenchido('+count+','+memoria_calculo_bloco_id+');putSessionMemoriaDeCalculo('+memoria_calculo_bloco_id+','+"'"+estrutura+"'"+','+"'"+pavimento+"'"+','+"'"+trecho+"'"+','+estrutura_id+','+"'"+torre+"'"+','+"'"+memoria_calculo+"'"+');" \
                        onfocus="cancelarEdicao();">\
                    </td>\
                    <td>\
                        <button onclick="removerLinha('+count+', '+memoria_calculo_bloco_id+
                    ');" class="btn btn-flat btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" '+
                        ' title="Remover" type="button">\
                            <i class="fa fa-remove fa-fw" aria-hidden="true"></i>\
                        </button>\
                    </td>\
                </tr>\
            ');
            recarregarMascara();
            array_blocos_previstos.push(memoria_calculo_bloco_id);

            $('.select2_add').select2();

            var tarefa_referencia = $('#tarefa_referencia').val();

            if(tarefa_referencia) {
                $('#planejamento_id_'+memoria_calculo_bloco_id).val(tarefa_referencia).trigger('change');
            }
        }
        abreEdicaoFacil(memoria_calculo_bloco_id, estrutura, pavimento, trecho);


        $('#td_bloco_'+memoria_calculo_bloco_id).attr('style','border: 2px solid #f98d00 !important;');
    }

    // Função para remover linha da tabela
    function removerLinha(id, bloco_id) {
        $('#linha_'+id).remove();
        var index = array_blocos_previstos.indexOf(bloco_id);

        if (index > -1) {
            array_blocos_previstos.splice(index, 1);
        }

        quantidadeDistribuida(function(){
            quantidade_distribuida = 0;
        });

        forgetSessionMemoriaDeCalculo(bloco_id);
    }

    // Função para excluir a linha do banco e da tabela
    function excluirLinha(id, bloco_id) {
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
                removerLinha(id, bloco_id);
            });
        });
    }

    // Interação entre quantidade e porcentagem.
    function calcularPorcentagem(qtd, id) {
        porcentagem =  (moneyToFloat(qtd) / qtd_item_apropriacao) * 100;

        if(porcentagem.length <= 2) {
            porcentagem = porcentagem+',00'
        } else {
            porcentagem = porcentagem.toFixed(2)
        }

        $('#porcentagem_'+id).val(porcentagem.replace('.', ','));

        recarregarMascara();

        quantidadeDistribuida(function(){
            quantidade_distribuida = 0;
        });
    }

    // Interação entre porcentagem e quantidade.
    function calcularQuantidade(porcentagem, id) {
        quantidade = qtd_item_apropriacao * (moneyToFloat(porcentagem) / 100);

        if(quantidade.length <= 2) {
            quantidade = quantidade+',00'
        } else {
            quantidade = quantidade.toFixed(2)
        }

        $('#quantidade_'+id).val(quantidade.replace('.', ','));

        recarregarMascara();

        quantidadeDistribuida(function(){
            quantidade_distribuida = 0;
        });
    }

    // Calcula a quantidade distribuida
    function quantidadeDistribuida(callback) {
        $('.calc_quantidade').each(function(index, objeto) {
            quantidade_distribuida += moneyToFloat(objeto.value);
        });

        $('#distribuida').text(floatToMoney(quantidade_distribuida, ''));

        a_distribuir = {{$contrato_item_apropriacao->qtd}} - quantidade_distribuida;

        $('#a_distribuir').text(floatToMoney(a_distribuir, ''));
        $('#qtd_a_distribuir').val(a_distribuir);

        return callback();
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

    function filtrarCheck() {
        var preenchido = $('#filtro_preenchido:checked').length;
        var nao_preenchido = $('#filtro_nao_preenchido:checked').length;

        $('.todosTrechos').each(function (index, value) {
            $('#td_bloco_'+value.value).click();
        });

        if(preenchido && !nao_preenchido) {
            $('.preenchido').show();
            $('.nao-preenchido').hide();
        }

        if(nao_preenchido && !preenchido) {
            $('.nao-preenchido').show();
            $('.preenchido').hide();
        }

        if(preenchido && nao_preenchido) {
            $('.preenchido').show();
            $('.nao-preenchido').show();
        }

        if(!preenchido && !nao_preenchido) {
            $('.preenchido').show();
            $('.nao-preenchido').hide();
        }
    }

    function verificarPreenchido(id, mem_calc_id) {
        var data = $('#data_'+mem_calc_id).val();
        var quantidade = $('#quantidade_'+mem_calc_id).val();
        var linha = $('#linha_'+id);

        if(quantidade == '0,00') {
            quantidade = null;
        }

        if(data && quantidade) {
            linha.removeClass('nao-preenchido');
            linha.addClass('preenchido');
        } else {
            linha.removeClass('preenchido');
            linha.addClass('nao-preenchido');
        }
    }

    function buscarMemoriaDeCalculo(memoria_de_calculo_id) {
        startLoading();

        history.pushState("", document.title, location.pathname+'?memoria_de_calculo='+memoria_de_calculo_id+'&torre='+$('#obra_torre_id').val());
        location.reload();

        stopLoading();
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

                            trechosTD += '<td onclick="adicionarNaTabela('+previsao_bloco_id+','+previsao_estrutura+','+previsao_pavimento+','+previsao_trecho+','+previsao_estrutura_id+', 0)" id="td_bloco_'+previsao_bloco_id+'" style="cursor:pointer;">&nbsp;' + trechoPav.nome + '&nbsp;</td>';
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
        $.ajax('/nomeclatura-mapas/json?modo=' + valor)
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

    /*
    * Ao trocar a torre, se houver algum item previsto na tabela, já carrega a MC e não deixa limpar o campo de escolha do mesmo.
    * Se o item for removido, deixa limpar o campo de MC.
    * Se não houver item previsto limpa o campo de MC.
    */
    function selecionaTorre(value) {
        startLoading();

        if(0) {
        // Carrega a MC e não deixa limpar o campo de escolha do mesmo.
            history.pushState("", document.title, location.pathname+'?memoria_de_calculo='+$('#memoria_de_calculo').val()+'&torre='+value);
            location.reload();
        } else if(0) {
        // Libera para limpar o campo de MC.
        } else {
        // Limpa o campo de MC.
            history.pushState("", document.title, location.pathname+'?memoria_de_calculo='+'&torre='+value);
            location.reload();
        }

        stopLoading();
    }

    function putSessionMemoriaDeCalculo(memoria_calculo_bloco_id, estrutura, pavimento, trecho, estrutura_id, torre, memoria_calculo) {
        $.ajax({
            url : "{{ route('memoriaCalculos.putSessionMemoriaDeCalculo') }}",
            type : 'POST',
            data : {
                contrato_id : '{{$contrato->id}}',
                contrato_item_apropriacao_id : '{{$contrato_item_apropriacao->id}}',
                memoria_calculo_bloco_id : memoria_calculo_bloco_id,
                estrutura : estrutura,
                pavimento : pavimento,
                trecho : trecho,
                estrutura_id : estrutura_id,
                data : $('#data_'+memoria_calculo_bloco_id).val(),
                quantidade : $('#quantidade_'+memoria_calculo_bloco_id).val(),
                planejamento_id : $('#planejamento_id_'+memoria_calculo_bloco_id).val(),
                torre : torre,
                memoria_calculo : memoria_calculo
            }
        });
    }

    function forgetSessionMemoriaDeCalculo(memoria_calculo_bloco_id) {
        $.ajax({
            url : "{{ route('memoriaCalculos.forgetSessionMemoriaDeCalculo') }}",
            type : 'POST',
            data : {
                contrato_id : '{{$contrato->id}}',
                contrato_item_apropriacao_id : '{{$contrato_item_apropriacao->id}}',
                memoria_calculo_bloco_id : memoria_calculo_bloco_id
            }
        });
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