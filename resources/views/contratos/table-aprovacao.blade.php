<hr>
<div class="row total-header">
    <div class="col-sm-3 text-right borda-direita">
        <h5>Valor previsto no orçamento</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ number_format($orcamentoInicial,2,',','.') }}
        </h4>
    </div>
    <div class="col-sm-3 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
        <h5>Valor comprometido realizado</h5>
        <h4>
            <small class="pull-left">R$</small>0,00
            {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
            {{--                    {{ number_format($realizado,2,',','.') }}--}}
        </h4>
    </div>
    <div class="col-sm-3 text-right borda-direita" title="Nos itens desta O.C.">
        <h5>Valor comprometido à gastar</h5>
        <h4>
            <small class="pull-left">R$</small>0,00
            {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação, como ainda não exixte contrato gerado, tem q estar zerado--}}
            {{--                    {{ number_format($totalAGastar,2,',','.') }}--}}
        </h4>
    </div>
    <div class="col-sm-3 text-right borda-direita" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
        <h5>SALDO DE ORÇAMENTO</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ number_format($orcamentoInicial,2,',','.') }}
            {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
            {{--{{ number_format($saldo,2,',','.') }}--}}
        </h4>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#oc"
                data-toggle="tab"
                class="js-tooltip"
                title="Insumos que foram originados de uma ordem de Compra">
                Originados da O.C.
            </a>
        </li>
        @if($itens->contrato_itens->isNotEmpty())
            <li>
                <a href="#contrato"
                    class="js-tooltip"
                    title="Insumos que foram adicionados ao gerar o contrato"
                    data-toggle="tab">
                    Originados do Contrato
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="oc">
            <table class="table table-bordered table-no-margin">
                <thead>
                    <tr>
                        <th class="text-center">Código do insumo</th>
                        <th class="text-center">Descrição do insumo</th>
                        <th class="text-center">Un. de medida</th>
                        <th class="text-center">Qtd.</th>
                        <th class="text-center">Valor Unitário</th>
                        <th class="text-center">Valor Total</th>
                        <th class="text-center">Status da qtd. do insumo</th>
                        <th class="text-center">Status do valor do insumo</th>
                        <th class="text-center">Status Serviço</th>
                        <th class="text-center">Acaba a obra</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens->oc_itens as $item)
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
                            <td class="text-center">{{ $item->insumo->nome }}</td>
                            <td class="text-center">{{ $item->insumo->unidade_sigla }}</td>
                            <td class="text-center">{{ float_to_money($item->qtd, '') }}</td>
                            <td class="text-center">{{ float_to_money($item->contratoItem->valor_unitario) }} </td>
                            <td class="text-center">{{ float_to_money($item->contratoItem->valor_unitario * $item->qtd) }} </td>
                            <td class="text-center">
                                {{--CONTA = saldo - previsto no orçamento--}}
                                <i class="fa fa-circle
                                    {{ ($item->qtd_inicial - $item->qtd_realizado - $item->qtd_inicial) < 0
                                        ? 'red'
                                        : 'green'
                                    }}">
                                </i>
                            </td>
                            <td class="text-center">
                                {{--CONTA = saldo - previsto no orçamento--}}
                                <i class="fa fa-circle
                                    {{ ($item->preco_inicial - $item->valor_realizado - $item->preco_inicial) < 0
                                        ? 'red'
                                        : 'green'
                                    }}"></i>
                            </td>
                            <td class="text-center">
                                @if($item->servico)
                                    <a href="/ordens-de-compra/detalhes-servicos/{{$contrato->obra_id}}/{{$item->servico->id}}" style="cursor:pointer;">
                                        <i class="fa fa-circle {{ (money_to_float($item->valor_servico) - money_to_float($item->valor_realizado)) - money_to_float($item->valor_servico) < 0 ? 'red': 'green'  }}"></i>
                                        <button class="btn btn-warning btn-sm btn-flat">Análise</button>
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
                                <a href="{{ route('contratos.memoria_de_calculo', [$contrato->id, $item->id]) }}" type="button"
                                        class="btn btn-flat btn-sm btn-primary"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Criar previsão de memória de cálculo">
                                    <i class="fa fa-calculator fa-fw" aria-hidden="true"></i>
                                </a>
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
                                                    <th class="text-center">Emergencial</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">{{ number_format($item->qtd_inicial, 2, ',','.') }}</td>
                                                    <td class="text-center">
                                                        {{ number_format(doubleval($item->qtd_realizada), 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{--{{ number_format( $item->qtd_inicial-doubleval($item->qtd_realizada), 2, ',','.') }}--}}0,00
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format( $item->qtd_inicial - doubleval($item->qtd_realizada), 2, ',','.') }}
                                                    </td>
                                                    <td class="text-center">{!! $item->emergencial?'<strong class="text-danger"> <i class="fa fa-exclamation-circle"></i> SIM</strong>':'NÃO' !!}</td>
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
                                                        {{--{{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}--}}0,00
                                                    </td>
                                                    <td class="text-center">
                                                        <small class="pull-left">R$</small>
                                                        {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}
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
                            <th>Serviço</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itens->contrato_itens as $item)
                            <tr>
                                <td>{{ $item->insumo->codigo }}</td>
                                <td>{{ $item->insumo->nome }}</td>
                                <td>{{ float_to_money($item->qtd) }}</td>
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
                                                    <th>Código Estruturado</th>
                                                    <th>Insumo</th>
                                                    <th>Valor Apropriado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->apropriacoes as $apropriacao)
                                                    <tr>
                                                        <td>{{ $apropriacao->codigoServico() }}</td>
                                                        <td>{{ $apropriacao->ligacao->insumo->codigo }} - {{ $apropriacao->ligacao->insumo->nome }}</td>
                                                        <td>{{ float_to_money($apropriacao->qtd) }}</td>
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
