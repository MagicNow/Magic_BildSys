<style>
    .nav-tabs-custom > .nav-tabs > li.active > a, .nav-tabs-custom > .nav-tabs > li.active:hover > a {
        color: #f98d00 !important;
    }

    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #f98d00 !important;
    }
</style>

<div class="row total-header" style="margin-left: 5px;">
    <div class="col-sm-3 text-left">
        <h3 class="fs18">Valor previsto no orçamento</h3>
      
        <h3 class="fs18" style="margin-top: -8px;">
            R$
            {{ number_format($orcamentoInicial,2,',','.') }}
        </h3>
    </div>
    <div class="col-sm-3 text-left" title="Até o momento em todos os itens desta O.C.">
        <h3 class="fs18">Valor comprometido realizado</h3>
         <h3 class="fs18" style="margin-top: -8px;">R$ 0,00
            {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
            {{--                    {{ number_format($realizado,2,',','.') }}--}}
        </h3>
    </div>
    <div class="col-sm-3 text-left" title="Nos itens desta O.C." style="margin-left: 10px;">
        <h3 class="fs18">Valor comprometido à gastar</h3>
         <h3 class="fs18" style="margin-top: -8px;">R$
            {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação--}}
            <span id="valor_comprometido_a_gastar_total"></span>
        </h3>
    </div>
    <div class="col-sm-3 text-left" title="Restante do Orçamento Inicial em relação aos itens desta O.C." style="width:170px;margin-left: 5px;">
       <h3 class="fs18">SALDO DE ORÇAMENTO</h3>
        <h3 class="fs18" style="margin-top: -8px;">R$
            <span id="saldo_de_orcamento_total"></span>
            {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
            {{--{{ number_format($saldo,2,',','.') }}--}}
        </h3>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#oc"
                data-toggle="tab"
                class="js-tooltip"
                title="Insumos que foram originados na O.C" style="color: #a94442;">
                Originado na O.C
            </a>
        </li>
        @if($itens->contrato_itens->isNotEmpty())
            <li>
                <a href="#contrato"
                    class="js-tooltip"
                    title="Insumos que foram adicionados ao gerar o contrato"
                    data-toggle="tab" style="color: #a94442;">
                    Originado no Contrato
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="oc">
            <table class="table table-bordered table-no-margin">
                <thead>
                    <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Descrição</th>
                        <th class="text-center">Un. de medida</th>
                        <th class="text-center">Qtd.</th>
                        <th class="text-center">Valor Unitário</th>
                        <th class="text-center">Valor Total</th>
                        <th class="text-center">Qtd. do insumo</th>
                        <th class="text-center">Valor do insumo</th>
                        <th class="text-center">Serviço</th>
                        <th class="text-center">Acaba a obra</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @php $valor_comprometido_a_gastar_total = 0; @endphp
                    @php $saldo_de_orcamento_total = 0; @endphp

                    @foreach($itens->oc_itens as $item)
                        @php $valor_comprometido_a_gastar = 0; @endphp
                        @php $qtd_comprometida_a_gastar_item = 0; @endphp
                        @php $valor_comprometido_a_gastar_item = 0; @endphp

                        @if($itens->contrato_itens->isNotEmpty())
                            @foreach($itens->contrato_itens as $c_item)
                                @php
                                    $valor_comprometido_a_gastar += $c_item->apropriacoes
                                        ->where('grupo_id', $item->grupo_id)
                                        ->where('subgrupo1_id', $item->subgrupo1_id)
                                        ->where('subgrupo2_id', $item->subgrupo2_id)
                                        ->where('subgrupo3_id', $item->subgrupo3_id)
                                        ->where('servico_id', $item->servico_id)
                                        ->sum('qtd');
                                @endphp
                            @endforeach
                        @endif
                        @php
                            $valor_comprometido_a_gastar += $item->contratoItem->valor_unitario * $item->qtd;

                            if($item->ordemDeCompraItem) {
                                $qtd_comprometida_a_gastar_item = money_to_float(\App\Repositories\OrdemDeCompraRepository::qtdComprometidaAGastarItem($item->ordemDeCompraItem->grupo_id, $item->ordemDeCompraItem->subgrupo1_id, $item->ordemDeCompraItem->subgrupo2_id, $item->ordemDeCompraItem->subgrupo3_id, $item->ordemDeCompraItem->servico_id, $item->ordemDeCompraItem->insumo_id, $item->ordemDeCompraItem->obra_id, $item->ordemDeCompraItem->id, $item->ordemDeCompraItem->ordemDeCompra->dataUltimoPeriodoAprovacao()));
                                $valor_comprometido_a_gastar_item = \App\Repositories\OrdemDeCompraRepository::valorComprometidoAGastarItem($item->ordemDeCompraItem->grupo_id, $item->ordemDeCompraItem->subgrupo1_id, $item->ordemDeCompraItem->subgrupo2_id, $item->ordemDeCompraItem->subgrupo3_id, $item->ordemDeCompraItem->servico_id, $item->ordemDeCompraItem->insumo_id, $item->ordemDeCompraItem->obra_id, $item->ordemDeCompraItem->id, $item->ordemDeCompraItem->ordemDeCompra->dataUltimoPeriodoAprovacao());
                            }

                            $valor_comprometido_a_gastar += $valor_comprometido_a_gastar_item;
                            $item->qtd += $qtd_comprometida_a_gastar_item;

                            $valor_comprometido_a_gastar_total += $valor_comprometido_a_gastar;
                            $saldo_de_orcamento_total += $item->preco_inicial - doubleval($item->valor_realizado) - $valor_comprometido_a_gastar
                        @endphp
                        <tr>
                            <td class="text-center">
                                <span data-toggle="tooltip" data-placement="right" data-html="true"
                                    title="
                                    {{ $item->grupo->codigo.' - '.$item->grupo->nome }}<br/>
                                    {{ $item->subgrupo1->codigo.' - '.$item->subgrupo1->nome }}<br/>
                                    {{ $item->subgrupo2->codigo.' - '.$item->subgrupo2->nome }}<br/>
                                    {{ $item->subgrupo3->codigo.' - '.$item->subgrupo3->nome }}<br/>
                                    {{ $item->servico->codigo.' - '.$item->servico->nome }}
                                    ">
                                    {{ $item->insumo->codigo }}</span>
                            </td>
                            <td class="text-left">{{ $item->insumo->nome }}</td>
                            <td class="text-center">{{ $item->insumo->unidade_sigla }}</td>
                            <td class="text-center">{{ float_to_money($item->qtd, '') }}</td>
                            <td class="text-center">{{ float_to_money($item->contratoItem->valor_unitario) }} </td>
                            <td class="text-center">{{ float_to_money($item->contratoItem->valor_unitario * $item->qtd) }} </td>
                            <td class="text-center">
                                <i class="fa fa-circle
                                    {{ (money_to_float($item->qtd_inicial) - $item->qtd_realizada - $item->qtd) < 0
                                        ? 'red'
                                        : 'green'
                                    }}">
                                </i>
                            </td>
                            <td class="text-center">
                                <i class="fa fa-circle
                                    {{ ($item->preco_inicial - doubleval($item->valor_realizado) - $valor_comprometido_a_gastar) < 0
                                        ? 'red'
                                        : 'green'
                                    }}"></i>
                            </td>
                            <td class="text-center">
                                @if($item->servico)
                                    @php
                                        $calculos_servico = \App\Repositories\OrdemDeCompraRepository::calculosDetalhesServicos($contrato->obra_id, $item->servico->id);
                                    @endphp
                                    <i class="fa fa-circle {{ $calculos_servico['saldo_disponivel'] < 0 ? 'red': 'green'  }}" aria-hidden="true"></i>
                                    <a href="/ordens-de-compra/detalhes-servicos/{{$contrato->obra_id}}/{{$item->servico->id}}" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Análise">
                                        <i class="fa fa-info-circle text-info" style="font-size: 20px;"></i>
                                    </a>
                                @else
                                    <i class="fa fa-circle {{ (money_to_float($item->valor_servico) - money_to_float($item->valor_realizado)) - money_to_float($item->valor_servico) < 0 ? 'red': 'green'  }}"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <span data-toggle="tooltip"
                                    data-placement="right"
                                    data-container="body"
                                    data-html="true"
                                    title="{{ $item->motivo_nao_finaliza_obra }}">
                                    {{ $item->total ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-flat btn-sm btn-warning"
                                    title="Expandir"
                                    onclick="showHideInfoExtra({{ $item->id }})">
                                    <i id="icone-expandir{{ $item->id }}"
                                        class="fa fa-caret-right fa-fw"></i>
                                </button>

                                @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */)
                                    <a href="{{ route('contratos.memoria_de_calculo', [$contrato->id, $item->id]) }}" type="button"
                                            class="btn btn-flat btn-sm btn-primary"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Criar previsão de memória de cálculo">
                                        <i class="fa fa-calculator fa-fw" aria-hidden="true"></i>
                                    </a>
                                @endif
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">{{ number_format($item->qtd_inicial, 2, ',','.') }}</td>
                                                    <td class="text-center">
                                                        {{ number_format(doubleval($item->qtd_realizada), 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ float_to_money($item->qtd, '') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format( money_to_float($item->qtd_inicial) - $item->qtd_realizada - $item->qtd, 2, ',','.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 table-responsive margem-topo">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Valor previsto no orçamento</th>
                                                    <th class="text-center">Valor comprometido realizado</th>
                                                    <th class="text-center">Valor comprometido à gastar</th>
                                                    <th class="text-center">Saldo de valor do orçamento</th>
                                                    <th class="text-center">Insumo Principal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center"><small class="pull-left">R$</small> {{ number_format($item->preco_inicial, 2, ',','.') }}</td>
                                                    <td class="text-center">
                                                        <small class="pull-left">R$</small>
                                                        {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <small class="pull-left">R$</small>
                                                        {{ number_format( $valor_comprometido_a_gastar, 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <small class="pull-left">R$</small>
                                                        {{ number_format( $item->preco_inicial - doubleval($item->valor_realizado) - $valor_comprometido_a_gastar, 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->trocado)
                                                            {{ $item->insumo_troca_nome }}
                                                        @else
                                                            Não é troca
                                                        @endif
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
                                        </div>
                                    </div>
                                    <div class="col-md-6 margem-topo">
                                        @if($item->anexos->isNotEmpty())
                                            <div class="col-md-4 label-bloco">
                                                Arquivos anexos:
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    @foreach($item->anexos as $anexo)
                                                        <div class="bloco-texto-linha col-md-9"> {{ substr($anexo->arquivo, strrpos($anexo->arquivo,'/') + 1)  }}</div>
                                                        <div class="col-md-2">
                                                            <a href="{{ Storage::url($anexo->arquivo) }}" class="btn btn-default btn-block" target="_blank" >
                                                                <i class="fa fa-eye"></i>
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
        </div>
        @if($itens->contrato_itens->isNotEmpty())
            <div class="tab-pane fade" id="contrato">
                <table class="table table-bordered table-all-center table-no-margin">
                    <thead>
                        <tr>
                            <th>Código do Insumo</th>
                            <th>Descrição do Insumo</th>
                            <th>Valor</th>
                            <th>%</th>
                            <th>Apropriação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itens->contrato_itens as $item)
                            <tr>
                                <td>{{ $item->insumo->codigo }}</td>
                                <td>{{ $item->insumo->nome }}</td>
                                <td>{{ float_to_money($item->qtd * $item->valor_unitario) }}</td>
                                <td>
                                    @if($item->porcentagem)
                                        {{ $item->porcentagem }}%
                                    @else
                                        NÃO
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-flat btn-sm btn-warning" title="Expandir"
                                        onclick="showHideInfoExtra('contrato-{{ $item->id }}')">
                                        <i id="icone-expandircontrato-{{ $item->id }}"
                                           class="fa fa-caret-right fa-fw"></i>
                                    </button>
                                </td>
                                <tr id="dados-extrascontrato-{{ $item->id }}" style="display: none;">
                                    <td colspan="5">
                                        <table class="table table-bordered table-condensed table-all-center">
                                            <thead>
                                                <tr>
                                                    <th>Serviço</th>
                                                    <th>Insumo</th>
                                                    <th>Valor Apropriado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->apropriacoes as $apropriacao)
                                                    <tr>
                                                        <td>{{ $apropriacao->codigoServico() }}</td>
                                                        <td>{{ $apropriacao->ligacao->insumo->codigo }} - {{ $apropriacao->ligacao->insumo->nome }}</td>
                                                        <td>{{ float_to_money($apropriacao->qtd * $item->valor_unitario) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $(function(){
            $('#valor_comprometido_a_gastar_total').text('{{float_to_money($valor_comprometido_a_gastar_total)}}');
            $('#saldo_de_orcamento_total').text('{{float_to_money($saldo_de_orcamento_total)}}');
        })
    </script>
@endsection