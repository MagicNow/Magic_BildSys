@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }

        .perpage {
            font-weight: 100;
        }
      
    </style>
@stop
@section('content')
    <section class="content-header ">
        <h1>
            <a href="/" type="button" class="btn btn-link">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Detalhe da Ordem de Compra
            <div class="pull-right" style="margin-top: -2px;">
                <a href="/retroalimentacao" class="btn btn-info btn-lg btn-flat" style="padding-top: 2px;padding-bottom: 3px;">
                    Retroalimentação
                </a>
                @if(!is_null($ordemDeCompra->aprovado))
                    @if($ordemDeCompra->aprovado)
                        <span class="btn-lg btn-flat text-success" title="Aprovado">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </span>
                    @else
                        <span class="text-danger btn-lg btn-flat" title="Reprovado">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
                    @endif
                @else
                    @if($aprovavelTudo['podeAprovar'])
                        @if($aprovavelTudo['iraAprovar'])
                            <div class="btn-group" role="group" id="blocoOCAprovacao{{ $ordemDeCompra->id }}" aria-label="...">
                                <button type="button" title="Aprovar todos os itens"
                                        onclick="workflowAprovaReprova({{ $ordemDeCompra->id }},'OrdemDeCompraItem',1,'blocoOCAprovacao{{ $ordemDeCompra->id }}','OC {{ $ordemDeCompra->id }}', {{ $ordemDeCompra->id }}, 'OrdemDeCompra', 'itens');"
                                        class="btn btn-success btn-lg btn-flat">
                                    Aprovar
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button" title="Reprovar todos os itens"
                                        onclick="workflowAprovaReprova({{ $ordemDeCompra->id }},'OrdemDeCompraItem',0, 'blocoOCAprovacao{{ $ordemDeCompra->id }}','OC {{ $ordemDeCompra->id }}', {{ $ordemDeCompra->id }}, 'OrdemDeCompra', 'itens');"
                                        class="btn btn-danger btn-lg btn-flat">
                                    Reprovar
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        @else
                            @if($aprovavelTudo['jaAprovou'])
                                @if($aprovavelTudo['aprovacao'])
                                    <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </span>
                                @else
                                    <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </span>
                                @endif
                            @else
                                {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                                <button type="button" title="{{ $aprovavelTudo['msg'] }}"
                                        onclick="swal('{{ $aprovavelTudo['msg'] }}','','info');"
                                        class="btn btn-default btn-lg btn-flat">
                                    <i class="fa fa-info" aria-hidden="true"></i>
                                </button>
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        </h1>    
    </section>

    <div style="clear:both"></div>
    <div class="content detalhes">
        <h6 class="mb6 mt0">Dados Informativos</h6>
        <div class="row">
            <div class="col-md-2 form-group">
                {!! Form::label('id', 'Código da O.C.') !!}
                <p class="form-control input-lg highlight text-center">{!! $ordemDeCompra->id !!}</p>
            </div>

            <div class="col-md-4 form-group">
                {!! Form::label('obra', 'Obra') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->obra->nome !!}</p>
            </div>
            <div class="col-md-2 form-group">
                {!! Form::label('created_at', 'Data de Criação') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->created_at->format('d/m/Y') !!}</p>
            </div>
            <div class="col-md-4 form-group">
                {!! Form::label('user_id', 'Responsável') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->user->name !!}</p>
            </div>

            <div class="col-md-12">
                <div class="panel panel-default panel-body mb0 pt0 pb0" style="padding-bottom:0px;padding-top: 5px;margin-bottom: 0px;">
                    <h4 class="highlight mb0">Timeline</h4>
                    @if($alcadas_count)
                        @php $col_md = 12 / ($alcadas_count + 1); @endphp
                        <h4 class="fs18 col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                            <span>
                                Criação
                                {{ $ordemDeCompra->created_at->format('d/m/Y H:i') }}
                            </span>
                            <div class="progress mb0 mt6">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    100%
                                </div>
                            </div>
                        </h4>
                        @if(count($avaliado_reprovado))
                            @php
                                $count = 0;
                            @endphp
                            @foreach($avaliado_reprovado as $alcada)
                                @php
                                    $count += 1;
                                    $faltam_aprovar = $alcada['faltam_aprovar'];

                                    if(count($faltam_aprovar) > 1){
                                        $faltam_aprovar_texto = 'Faltam aprovar: ';
                                    }else{
                                        $faltam_aprovar_texto = 'Falta aprovar: ';
                                    }

                                    if(count($faltam_aprovar)){
                                        foreach ($faltam_aprovar as $nome_falta){
                                            $faltam_aprovar_texto .= $nome_falta.', ';
                                        }
                                    }
                                    $faltam_aprovar_texto = substr($faltam_aprovar_texto,0,-2);
                                @endphp
                                @if($alcada['aprovadores'])
                                    @if($alcada['total_avaliado'])
                                        @php
                                            $avaliado_aprovadores = $alcada['total_avaliado'] / $alcada['aprovadores'];
                                            $percentual_quebrado = $avaliado_aprovadores / $qtd_itens;
                                            $percentual = $percentual_quebrado * 100;
                                            $percentual = number_format($percentual, 0);

                                            if($percentual > 100){
                                                $percentual = 100;
                                            }
                                        @endphp

                                        <h4 class="fs18 col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                                            <span>
                                                {{$count}}ª alçada
                                                @if(isset($alcada['data_inicio']))
                                                    <small>{{ $alcada['data_inicio'] }}</small>
                                                @endif
                                            </span>
                                            @if($count == $alcadas_count)
                                                <span class="pull-right">Finalizada</span>
                                            @endif
                                            <div class="progress mb0 mt6" title="{{$faltam_aprovar_texto}}" data-toggle="tooltip" data-placement="top">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$percentual}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentual}}%;">
                                                    {{$percentual}}%
                                                </div>
                                            </div>
                                        </h4>
                                    @else
                                        <h4 class="fs18 col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                                            <span>{{$count}}ª alçada</span>
                                            @if($count == $alcadas_count)
                                                <span class="pull-right">Finalizada</span>
                                            @endif
                                            <div class="progress mb0 mt6" title="{{$faltam_aprovar_texto}}" data-toggle="tooltip" data-placement="top">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; color: black;">
                                                    0%
                                                </div>
                                            </div>
                                        </h4>
                                    @endif
                                @else
                                    <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                                        <span>{{$count}}ª alçada</span>
                                        @if($count == $alcadas_count)
                                            <span class="pull-right">
                                                Finalizada
                                                <small>{{ $ordemDeCompra->updated_at->format('d/m/Y H:i') }}</small>
                                            </span>
                                        @endif
                                        <div class="progress mb0 mt6" title="Essa alçada não possuí aprovadores" data-toggle="tooltip" data-placement="top">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; color: black;">
                                                0%
                                            </div>
                                        </div>
                                    </h4>
                                @endif
                            @endforeach
                        @else
                            @for($i = 1; $i <= $alcadas_count; $i ++)
                                <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                                    <span>{{$i}}ª alçada</span>
                                    @if($i == $alcadas_count)
                                        <span class="pull-right">{{$oc_status}}</span>
                                    @endif
                                    <div class="progress mb0 mt6">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                            100%
                                        </div>
                                    </div>
                                </h4>
                            @endfor
                        @endif
                    @endif
                </div>
            </div>
        </div>
       
        <div class="clearfix total-header mb6" id="totalInsumos">
            <div class="col-md-2 text-left">
                <h3 class="fs14 mt6 mb0">VALOR PREVISTO NO ORÇAMENTO</h3>
                <h3 class="fs17 mt0 mb0"><strong>R${{ number_format($orcamentoInicial,2,',','.') }}</strong></h3>
            </div>
            <div class="col-md-2 text-left" title="Até o momento em todos os itens desta O.C.">
                <h3 class="fs14 mt6 mb0">VALOR COMPROMETIDO REALIZADO</h3>
                <h3 class="fs17 mt0 mb0"><strong>R$ 0,00</strong>
                    {{---  @TODO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
                    {{--                    {{ number_format($realizado,2,',','.') }}--}}
                </h3>
            </div>
            <div class="col-md-2 text-left " title="Nos itens desta O.C.">
                <h3 class="fs14 mt6 mb0">VALOR COMPROMETIDO À GASTAR</h3>
                <h3 class="fs17 mt0 mb0"><strong>R$
                    {{---  @TODO = A gastar: É a soma de todos os saldos de contratos na que apropriação--}}
                    {{ number_format(floatval($valor_comprometido_a_gastar),2,',','.') }}</strong>
                </h3>
            </div>
            <div class="col-md-2 text-left" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h3 class="fs14 mt6 mb0">SALDO DE ORÇAMENTO</h3>
                <h3 class="fs17 mt0 mb0"><strong>R$
                    {{ number_format($orcamentoInicial - floatval($valor_comprometido_a_gastar),2,',','.') }}
                    {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
                    {{--{{ number_format($saldo,2,',','.') }}--}}</strong>
                </h3>
            </div>
            <div class="col-md-2 text-left">
                <h3 class="fs14 mt6 mb0">VALOR DA OC</h3>
                <h3 class="fs17 mt0 mb0"><strong>R${{ number_format($totalSolicitado,2,',','.') }}</strong></h3>
            </div>
            <div class="col-md-2 text-left">
                <h3 class="fs14 mt6 mb0">SALDO DISPONÍVEL APÓS O.C</h3>
                <h3 class="fs17 mt0 mb0"><strong>R${{ number_format(($orcamentoInicial - floatval($valor_comprometido_a_gastar) - $totalSolicitado),2,',','.') }}</strong>
                </h3>
            </div>
        </div>
        <div class="panel panel-default detalhe-compra-tabela">
            <div class="panel-body" style="padding:0px;">
                <div class="col-md-12 table-responsive mt14">
                    {!! Form::select('perPage', [10=>10, 25=>25, 50=>50, 100=>100, 1000=>1000], null, ['class' => 'col-md-1', 'onchange'=>'perPage(this.value);']) !!}&nbsp;{!! Form::label('perPage', ' RESULTADOS POR PÁGINA', ['class' => 'perpage']) !!}
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Descrição</th>
                            <th class="text-center">Un. de medida</th>
                            <th class="text-center">Qtd.</th>
                            <th class="text-center">Valor unitário</th>
                            <th class="text-center">Valor total</th>
                            <th class="text-center">Qtd. do insumo</th>
                            <th class="text-center">Valor do insumo</th>
                            <th class="text-center">Serviço</th>
                            <th class="text-center">Acaba a obra</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody>

                    @foreach($itens as $item)
                        {{--Se o insumo foi incluído no orçamento, o SALDO DE ORÇAMENTO fica com o valor comprado negativo.--}}
                        {{--@if($item->insumo_incluido)--}}
                            {{--@php $saldo_valor_orcamento = $farol_saldo_valor_orcamento = - money_to_float($item->valor_total); @endphp--}}
                        {{--@else--}}
                            @php $saldo_valor_orcamento = $item->substitui ? $item->valor_previsto_orcamento_pai-money_to_float($item->valor_realizado) : $item->preco_inicial-money_to_float($item->valor_realizado); @endphp
                        {{--@endif--}}

                        @php
                            $insumo_catalogo = \App\Repositories\OrdemDeCompraRepository::existeNoCatalogo($item->insumo_id, $item->obra_id);
                            $botao = '';

                            if($insumo_catalogo) {
                                $botao = '<button type="button" title="
                                            <b>Origem:</b> Catálogo '.$insumo_catalogo->id.'<br>'.
                                            '<b>Valor unitário:</b> '.float_to_money($insumo_catalogo->valor_unitario).'<br>'.
                                            '<b>Pedido mínimo:</b> '.float_to_money($insumo_catalogo->pedido_minimo, '').
                                            '<br> <b>Pedido múltiplo de:</b> '.float_to_money($insumo_catalogo->pedido_multiplo_de, '').'
                                                " data-toggle="tooltip" data-placement="top" data-html="true" class="btn btn-primary btn-sm" 
                                                style="border-radius: 9px !important;zoom:70%;width: 20px;height: 20px;padding: 0px;margin-left: 5px;">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </button>';
                            }
                        @endphp

                        <tr
                        @if($item->data_dispensa)
                            class="danger"
                        ><td colspan="11" class="text-left">
                                <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                Insumo dispensado após aprovação:<br>
                                <small>
                                    {{ $item->obs_dispensa }}
                                    <span class="label label-default">
                                        por {{ $item->userDispensa->name }}
                                    </span>
                                </small>
                            </td>
                            <tr class="danger"
                            @endif
                        >
                            <td class="text-center">
                                <span data-toggle="tooltip" data-placement="right" data-html="true"
                                    title="
                                        {{$item->grupo->codigo.' - '.$item->grupo->nome}}<br/>
                                        {{$item->subgrupo1->codigo.' - '.$item->subgrupo1->nome}}<br/>
                                        {{$item->subgrupo2->codigo.' - '.$item->subgrupo2->nome}}<br/>
                                        {{$item->subgrupo3->codigo.' - '.$item->subgrupo3->nome}}<br/>
                                        {{$item->servico->codigo.' - '.$item->servico->nome}}
                                        @if($item->substitui)
                                            <br/><i class='fa fa-exchange'></i> {{$item->substitui}}
                                        @endif
                                    ">
                                {{ $item->insumo->codigo }}</span>
                            </td>
                            <td class="text-center">
                                {{ $item->insumo->nome }}
                            </td>
                            <td class="text-center">
                                {{ $item->unidade_sigla }}
                            </td>
                            <td class="text-center">
                                {{ $item->qtd }}
                            </td>
                            <td class="text-center">
                                {{ float_to_money($item->valor_unitario) }}
                                {!! $botao !!}
                            </td>
                            <td class="text-center">
                                {{ float_to_money(money_to_float($item->valor_total)) }}
                            </td>
                            <td class="text-center">
                                {{-- Qntd Prevista - Qntd Realizada - Qntd Á gastar = Qntd Saldo do orçamento - Qntd OC --}}
                                @php
                                        $valor_comprometido_a_gastar_item = \App\Repositories\OrdemDeCompraRepository::valorComprometidoAGastarItem($item->grupo_id, $item->subgrupo1_id, $item->subgrupo2_id, $item->subgrupo3_id, $item->servico_id, $item->insumo_id, $item->obra_id, $item->id, $item->ordemDeCompra->dataUltimoPeriodoAprovacao());
                                        $qtd_prevista = $item->substitui ? $item->qtd_prevista_orcamento_pai : $item->qtd_inicial;
                                        $qtd_comprometida_a_gastar = money_to_float(\App\Repositories\OrdemDeCompraRepository::qtdComprometidaAGastarItem($item->grupo_id, $item->subgrupo1_id, $item->subgrupo2_id, $item->subgrupo3_id, $item->servico_id, $item->insumo_id, $item->obra_id, $item->id, $item->ordemDeCompra->dataUltimoPeriodoAprovacao()));
                                        $saldo_qtd_orcamento = $qtd_prevista - money_to_float($item->qtd_realizada) - $qtd_comprometida_a_gastar;

                                        $saldo_valor_orcamento -= $valor_comprometido_a_gastar_item;

                                        $status_qtd = $saldo_qtd_orcamento - money_to_float($item->qtd);
                                @endphp

                                @if($status_qtd > 0)
                                    <i class="fa fa-circle yellow" aria-hidden="true"></i>
                                @elseif($status_qtd < 0)
                                    <i class="fa fa-circle red" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-circle green" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                {{--CONTA = saldo - valor oc--}}
                                @php
                                    $status_insumo = $saldo_valor_orcamento - money_to_float($item->valor_total);
                                @endphp
                                <i class="fa fa-circle {{ $status_insumo < 0 ? 'red': 'green'  }}" aria-hidden="true"></i>
                            </td>
                            <td class="text-center">
                                @if($item->servico)
                                    @php
                                        $calculos_servico = \App\Repositories\OrdemDeCompraRepository::calculosDetalhesServicos($ordemDeCompra->obra_id, $item->servico->id, $ordemDeCompra->id);
                                    @endphp
                                    <i class="fa fa-circle {{ $calculos_servico['saldo_disponivel'] < 0 ? 'red': 'green'  }}" aria-hidden="true"></i>
                                    <a href="/ordens-de-compra/detalhes-servicos/{{$ordemDeCompra->obra_id}}/{{$item->servico->id}}?oc_id={{$ordemDeCompra->id}}" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Análise">
                                        <i class="fa fa-info-circle text-info" style="font-size: 14px;"></i>
                                    </a>
                                @else
                                    <i class="fa fa-circle {{ (money_to_float($item->valor_servico) - $valor_comprometido_a_gastar_servico - money_to_float($item->valor_servico_oc)) < 0 ? 'red': 'green'  }}" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <span data-toggle="tooltip" data-placement="left" data-container="body" data-html="true" title="{{$item->motivo_nao_finaliza_obra}}">{{ $item->total ? 'Sim' : 'Não' }}</span>
                            </td>
                            <td class="text-center acoes" style="width: 10%">
                                <div class="btn-group" role="group" aria-label="...">
                                    @if(!is_null($item->aprovado))
                                        @if($item->aprovado)
                                            <span
                                                    class="btn btn-success btn-sm btn-flat ocItemTimeline"
                                                    data-id="{{ $item->id }}" data-workflow-tipo="1">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </span>
                                        @else
                                            <span  disabled="disabled"
                                                    class="btn btn-danger btn-sm btn-flat ocItemTimeline"
                                                    data-id="{{ $item->id }}" data-workflow-tipo="1">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                    @else
                                        <button type="button" title="Ver detalhes de aprovação"
                                                class="btn btn-sm btn-default btn-flat ocItemTimeline"
                                                data-id="{{ $item->id }}" data-workflow-tipo="1">
                                            <i class="fa fa-fw fa-hourglass-half"></i>
                                        </button>
                                        <?php
                                        $workflowAprovacao = \App\Repositories\WorkflowAprovacaoRepository::verificaAprovacoes('OrdemDeCompraItem', $item->id, Auth::user());
                                        ?>
                                        @if($workflowAprovacao['podeAprovar'])
                                            @if($workflowAprovacao['iraAprovar'])
                                                <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $item->id }}" aria-label="...">
                                                    <button type="button" onclick="workflowAprovaReprova({{ $item->id }},'OrdemDeCompraItem',1,'blocoItemAprovaReprova{{ $item->id }}','Insumo {{ $item->insumo->codigo }}',0, '', '', true);"
                                                            class="btn btn-success btn-sm btn-flat"
                                                            title="Aprovar este item">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </button>
                                                    <button type="button" onclick="workflowAprovaReprova({{ $item->id }},'OrdemDeCompraItem',0, 'blocoItemAprovaReprova{{ $item->id }}','Insumo {{ $item->insumo->codigo }}',0, '', '', true);"
                                                            class="btn btn-danger btn-sm btn-flat"
                                                            title="Reprovar este item">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            @else
                                                @if($workflowAprovacao['jaAprovou'])
                                                    @if($workflowAprovacao['aprovacao'])
                                                        <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </span>
                                                    @else
                                                        <span class="text-danger btn-sm btn-flat" title="Reprovado por você">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </span>
                                                    @endif
                                                @else
                                                    {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                                                    <button style="line-height:0px;    padding: 4px 9px;" type="button" title="{{ $workflowAprovacao['msg'] }}"
                                                            onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
                                                            class="btn btn-default btn-sm btn-flat">
                                                        <i class="fa fa-info" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    <button type="button" class="btn btn-flat btn-sm btn-warning" title="Detalhar"
                                             onclick="showHideInfoExtra({{ $item->id }})">
                                        <i id="icone-expandir{{ $item->id }}" class="fa fa-caret-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr style="display: none;" id="dados-extras{{ $item->id }}">
                            <td colspan="11">
                                <div class="row">
                                    <div class="col-md-12 table-responsive margem-topo">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Qtd. prevista no orçamento</th>
                                                <th class="text-center">Qtd. comprometida realizada</th>
                                                <th class="text-center">Qtd. comprometida à gastar</th>
                                                <th class="text-center">Saldo de qtd. do orçamento</th>
                                                <th class="text-center">Qtd. da O.C.</th>
                                                <th class="text-center">Saldo de qntd disponível após O.C</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    {{ number_format($qtd_prevista, 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format(money_to_float($item->qtd_realizada), 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($qtd_comprometida_a_gastar, 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($saldo_qtd_orcamento, 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->qtd }}
                                                </td>
                                                <td class="text-center">
                                                    {{--SALDO DE QNTD DO ORÇAMENTO - QNTD DA O.C.--}}
                                                    {{ number_format($status_qtd , 2, ',','.')}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 table-responsive margem-topo">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">VALOR PREVISTO NO ORÇAMENTO</th>
                                                <th class="text-center">VALOR COMPROMETIDO REALIZADO</th>
                                                <th class="text-center">VALOR COMPROMETIDO À GASTAR</th>
                                                <th class="text-center">SALDO DE VALOR DO ORÇAMENTO</th>
                                                <th class="text-center">VVALOR DA O.C.</th>
                                                <th class="text-center">SALDO DE VALOR DISPONÍVEL APÓS O.C.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <small class="pull-left">R$</small> {{ number_format($item->substitui ? $item->valor_previsto_orcamento_pai : $item->preco_inicial, 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    <small class="pull-left">R$</small>
                                                    {{ number_format( money_to_float($item->valor_realizado), 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    <small class="pull-left">R$</small>
                                                    {{number_format($valor_comprometido_a_gastar_item, 2, ',','.')}}
                                                </td>
                                                <td class="text-center">
                                                    <small class="pull-left">R$</small>
                                                    {{ number_format($saldo_valor_orcamento, 2, ',','.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ float_to_money(money_to_float($item->valor_total)) }}
                                                </td>
                                                <td class="text-center">
                                                    {{--SALDO DE VALOR DO ORÇAMENTO - VALOR DA O.C.--}}
                                                    {{ float_to_money($saldo_valor_orcamento - floatval($item->valor_total)) }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6 margem-topo borda-direita">
                                        <div class="row">
                                            <div class="col-md-4 label-bloco">
                                                Justificativa de compra:
                                            </div>
                                            <div class="bloco-texto-conteudo col-md-7">
                                                {{ $item->justificativa }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 margem-topo">
                                        <div class="col-md-4 label-bloco">
                                            Observações ao fornecedor:
                                        </div>
                                        <div class="bloco-texto-conteudo col-md-7">
                                            {{ $item->obs }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 margem-topo borda-direita">
                                        <div class="row">
                                            <div class="col-md-4 label-bloco">
                                                Tabela TEMS:
                                            </div>
                                            <div class="bloco-texto-conteudo col-md-7">
                                                {{ $item->tems }}
                                            </div>

                                            <div class="col-md-4 label-bloco margem-topo">
                                                Contrato aditivado:
                                            </div>
                                            <div class="bloco-texto-conteudo col-md-7 margem-topo">
                                                {{ $item->sugestao_contrato_id }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 margem-topo">
                                        @if($item->anexos)
                                            <div class="col-md-4 label-bloco">
                                                Arquivos anexos:
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    @foreach($item->anexos as $anexo)
                                                        <div class="bloco-texto-linha col-md-9">{{ substr($anexo->arquivo, strrpos($anexo->arquivo,'/')+1  )  }}</div>
                                                        <div class="col-md-2">
                                                            <a href="{{ Storage::url($anexo->arquivo) }}" class="btn btn-default btn-block" target="_blank" >
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    <div class="text-center">
                        {{ $itens->links() }}
                    </div>
            </div>
        </div>
</div>
        <div class="modal fade" id="modal-alcadas" tabindex="-1" role="dialog"></div>
@endsection
@section('scripts')
    @parent
<script type="text/javascript">
    function perPage(number){
        history.pushState("", document.title, location.pathname+ '?perPage='+number);
        location.reload();
    }

    <?php
            $options_motivos = "<option value=''>Escolha...</option>";
            foreach($motivos_reprovacao as $motivo_id=>$motivo_nome){
                $options_motivos .= "<option value='".$motivo_id."'>".$motivo_nome."</option>";
            }
    ?>
    options_motivos = "{!! $options_motivos !!}";
    $(function () {
        var workflowTipo = $('.ocItemTimeline');

        workflowTipo.tooltip({
            title: 'Clique para ver detalhes',
            container: document.body
        });

        workflowTipo.on('click', function(event) {
            startLoading();
            $.get('/workflow/detalhes', event.currentTarget.dataset)
                    .always(stopLoading)
                    .done(function(data) {
                        $('#modal-alcadas').html(data);
                        $('#modal-alcadas').modal('show');
                    })
                    .fail(function() {
                        swal('Ops!', 'Ocorreu um erro ao mostrar os detalhes da alçada', 'error');
                    });
        });
    });
</script>
@stop
