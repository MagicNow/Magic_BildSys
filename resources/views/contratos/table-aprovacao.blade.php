<hr>
<div class="row" id="totalInsumos">
    <div class="col-md-2 col-20 text-right borda-direita">
        <h5>Valor previsto no orçamento</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ number_format($orcamentoInicial,2,',','.') }}
        </h4>
    </div>
    <div class="col-md-2 col-20 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
        <h5>Valor comprometido realizado</h5>
        <h4>
            <small class="pull-left">R$</small>0,00
            {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
            {{--                    {{ number_format($realizado,2,',','.') }}--}}
        </h4>
    </div>
    <div class="col-md-2 col-20 text-right borda-direita" title="Nos itens desta O.C.">
        <h5>Valor comprometido à gastar</h5>
        <h4>
            <small class="pull-left">R$</small>0,00
            {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação, como ainda não exixte contrato gerado, tem q estar zerado--}}
            {{--                    {{ number_format($totalAGastar,2,',','.') }}--}}
        </h4>
    </div>
    <div class="col-md-2 col-20 text-right borda-direita" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
        <h5>SALDO DE ORÇAMENTO</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ number_format($orcamentoInicial,2,',','.') }}
            {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
            {{--{{ number_format($saldo,2,',','.') }}--}}
        </h4>
    </div>
    <div class="col-md-2 col-20 text-right">
        <h5>SALDO DISPONÍVEL</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{
                float_to_money(
                    $orcamentoInicial - $contrato->valor_atual_inicial,
                    ''
                )
            }}
        </h4>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Código do insumo</th>
                    <th class="text-center">Descrição do insumo</th>
                    <th class="text-center">Quantidade</th>
                    <th class="text-center">Und de medida</th>
                    <th class="text-center">Status da quantidade do insumo</th>
                    <th class="text-center">Status do valor do insumo</th>
                    <th class="text-center">Status Serviço</th>
                    <th class="text-center">Acaba a obra</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
        @foreach($itens as $item)
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
                <td class="text-center">{{ float_to_money($item->qtd, '') }}</td>
                <td class="text-center">{{ $item->insumo->unidade_sigla }}</td>
                <td class="text-center">
                    {{--CONTA = saldo - previsto no orçamento--}}
                    @if($item->ordem_de_compra_item_id)
                    <i class="fa fa-circle
                        {{ ($item->qtd_inicial - $item->qtd_realizado - $item->qtd_inicial) < 0
                            ? 'red'
                            : 'green'
                        }}">
                    </i>
                    @else
                        <button class="btn btn-flat btn-default btn-xs"
                            title="Insumo não originou de uma ordem de compra"
                            data-toggle="tooltip">
                            <i class="fa fa-info fa-fw"></i>
                        </button>
                    @endif
                </td>
                <td class="text-center">
                    {{--CONTA = saldo - previsto no orçamento--}}
                    @if($item->ordem_de_compra_item_id)
                        <i class="fa fa-circle
                            {{ ($item->preco_inicial - $item->valor_realizado - $item->preco_inicial) < 0
                                ? 'red'
                                : 'green'
                            }}"></i>
                    @else
                        <button class="btn btn-flat btn-default btn-xs"
                            title="Insumo não originou de uma ordem de compra"
                            data-toggle="tooltip">
                            <i class="fa fa-info fa-fw"></i>
                        </button>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->ordem_de_compra_item_id)
                        @if($item->servico)
                            <a href="/ordens-de-compra/detalhes-servicos/{{$contrato->obra_id}}/{{$item->servico->id}}" style="cursor:pointer;">
                                <i class="fa fa-circle {{ (money_to_float($item->valor_servico) - money_to_float($item->valor_realizado)) - money_to_float($item->valor_servico) < 0 ? 'red': 'green'  }}"></i>
                                <button class="btn btn-warning btn-sm btn-flat">Análise</button>
                            </a>
                        @else
                            <i class="fa fa-circle {{ (money_to_float($item->valor_servico) - money_to_float($item->valor_realizado)) - money_to_float($item->valor_servico) < 0 ? 'red': 'green'  }}"></i>
                        @endif
                    @else
                        <button class="btn btn-flat btn-default btn-xs"
                            title="Insumo não originou de uma ordem de compra"
                            data-toggle="tooltip">
                            <i class="fa fa-info fa-fw"></i>
                        </button>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->ordem_de_compra_item_id)
                        <span data-toggle="tooltip"
                            data-placement="right"
                            data-container="body"
                            data-html="true"
                            title="{{ $item->motivo_nao_finaliza_obra }}">
                            {{ $item->total ? 'Sim' : 'Não' }}
                        </span>
                    @else
                        <button class="btn btn-flat btn-default btn-xs"
                            title="Insumo não originou de uma ordem de compra"
                            data-toggle="tooltip">
                            <i class="fa fa-info fa-fw"></i>
                        </button>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        @if($item->ordem_de_compra_item_id)
                            <button type="button"
                                class="btn btn-flat btn-sm btn-warning"
                                title="Expandir"
                                onclick="showHideInfoExtra({{ $item->id }})">
                                <i id="icone-expandir{{ $item->id }}"
                                    class="fa fa-caret-right fa-fw fa-lg"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @if($item->ordem_de_compra_item_id)
            <tr style="display: none;" id="dados-extras{{ $item->id }}">
                <td colspan="9">
                    <div class="row">
                        <div class="col-md-12 table-responsive margem-topo">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Unidade Medida</th>
                                    <th class="text-center">Qntd prevista no orçamento</th>
                                    <th class="text-center">Valor previsto no orçamento</th>
                                    <th class="text-center">Qntd comprometida realizada</th>
                                    <th class="text-center">Valor comprometido realizado</th>
                                    <th class="text-center">Qntd comprometida à gastar</th>
                                    <th class="text-center">Valor comprometido à gastar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">{{ $item->insumo->unidade_sigla }}</td>
                                    <td class="text-center">{{ number_format($item->qtd_inicial, 2, ',','.') }}</td>
                                    <td class="text-center"><small class="pull-left">R$</small> {{ number_format($item->preco_inicial, 2, ',','.') }}</td>
                                    <td class="text-center">
                                        {{ number_format(doubleval($item->qtd_realizada), 2, ',','.') }}
                                    </td>
                                    <td class="text-center">
                                        <small class="pull-left">R$</small>
                                        {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}
                                    </td>
                                    <td class="text-center">
                                        {{--{{ number_format( $item->qtd_inicial-doubleval($item->qtd_realizada), 2, ',','.') }}--}}0,00
                                    </td>
                                    <td class="text-center">
                                        <small class="pull-left">R$</small>
                                        {{--{{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}--}}0,00
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 table-responsive margem-topo">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Saldo de qntd do orçamento</th>
                                    <th class="text-center">Saldo de valor do orçamento</th>
                                    <th class="text-center">Emergencial</th>
                                    <th class="text-center">Insumo Principal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{ number_format( $item->qtd_inicial - doubleval($item->qtd_realizada), 2, ',','.') }}
                                    </td>
                                    <td class="text-center">
                                        <small class="pull-left">R$</small>
                                        {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}
                                    </td>
                                    <td class="text-center">{!! $item->emergencial?'<strong class="text-danger"> <i class="fa fa-exclamation-circle"></i> SIM</strong>':'NÃO' !!}</td>
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
            @endif
        @endforeach
        </tbody>
    </table>
</div>
{{-- <div class="pg text-center"> --}}
{{--     {{ $itens->links() }} --}}
{{-- </div> --}}
