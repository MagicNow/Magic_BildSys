<div class="box box-muted">
    <div class="box-header with-border">
        Pendências
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-condensed table-all-center table-bordered table-no-margin">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="2" class="text-center">Antes</th>
                    <th colspan="2" class="text-center">Depois</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Movimentação</th>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Un</th>
                    <th>Qtd.</th>
                    <th>Valor Unitário</th>
                    <th>Qtd.</th>
                    <th>Valor Unitário</th>
                    <th>Data</th>
                    <th style="width: 20%">Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendencias as $modificacao)
                    @php $apropriacoes_id = []; @endphp
                    <tr>
                        <td>{{ $modificacao['tipo_modificacao'] }}</td>
                        <td>{{ $modificacao->item->insumo->codigo }}</td>
                        <td>{{ $modificacao->item->insumo->nome }}</td>
                        <td>{{ $modificacao->item->insumo->unidade_sigla }}</td>
                        <td>{{ float_to_money($modificacao['qtd_anterior'], '') }}</td>
                        <td>{{ float_to_money($modificacao['valor_unitario_anterior']) }}</td>
                        <td>{{ float_to_money($modificacao['qtd_atual'], '') }}</td>
                        <td>{{ float_to_money($modificacao['valor_unitario_atual']) }}</td>
                        <td>{{ $modificacao['created_at']->format('d/m/Y') }}</td>
                        <td>
                            @if($modificacao->workflow['podeAprovar'])
                                @if($modificacao->workflow['iraAprovar'])
                                    <span id="blocoItemAprovaReprovaItem{{ $modificacao->id }}">
                                        <button type="button" onclick="workflowAprovaReprova({{ $modificacao->id }},
                                                'ContratoItemModificacao',1,'blocoItemAprovaReprovaItem{{ $modificacao->id }}',
                                                '{{ $modificacao->tipo_modificacao }}', 0, '', '', true);"
                                                class="btn btn-success btn-xs btn-flat"
                                                title="Aprovar">
                                          Aprovar
                                          <i class="fa fa-check" aria-hidden="true"></i>
                                        </button>

                                        <button type="button" onclick="workflowAprovaReprova({{ $modificacao->id }},
                                                'ContratoItemModificacao',0, 'blocoItemAprovaReprovaItem{{ $modificacao->id }}',
                                                '{{ $modificacao->tipo_modificacao }}',0, '', '', true);"
                                                class="btn btn-danger btn-xs btn-flat"
                                                title="Reprovar Este item">
                                          Reprovar
                                          <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                @else
                                    @if($modificacao->workflow['jaAprovou'])
                                        @if($modificacao->workflow['aprovacao'])
                                            <span class="btn-xs btn-flat text-success" title="Aprovado por você">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </span>
                                        @else
                                            <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                    @else
                                        <button type="button" title="{{ $modificacao->workflow['msg'] }}"
                                                onclick="swal('{{ $modificacao->workflow['msg'] }}','','info');"
                                                class="btn btn-default btn-xs btn-flat">
                                            <i class="fa fa-info fa-fw" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                @endif
                            @endif

                            <button class="btn btn-xs btn-info btn-flat"
                                data-toggle="modal"
                                data-target="#detalhes-item-{{ $modificacao->id }}">
                              <span data-toggle="tooltip" title="Detalhes por Apropriação">
                                  Detalhes <i class="fa fa-plus fa-fw"></i>
                              </span>
                            </button>

                            @if($modificacao['tipo_modificacao'] == 'Reajuste')
                                <button class="btn btn-xs btn-warning btn-flat" data-toggle="modal"
                                        data-target="#analise-item-{{ $modificacao->id }}">
                                  <span data-toggle="tooltip" title="Análise do reajuste">
                                      Análise <i class="fa fa-eye fa-fw"></i>
                                  </span>
                                </button>
                            @endif
                            <div class="modal fade" id="detalhes-item-{{ $modificacao->id }}" tabindex="-1"
                                 role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">
                                                {{ $modificacao->tipo_modificacao }} <br>
                                                <small>{{ $modificacao->item->insumo->nome }}</small>
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-condensed table-all-center table-bordered table-no-margin">
                                                <thead>
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th colspan="2" class="text-center">Antes</th>
                                                    <th colspan="3" class="text-center">Depois</th>
                                                </tr>
                                                <tr>
                                                    <th>Código Estruturado</th>
                                                    <th>Anexo</th>
                                                    <th>Qtd.</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Qtd.</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Observação</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($modificacao->apropriacoes as $apropriacao)
                                                    @if($apropriacao->pivot->qtd_anterior != $apropriacao->pivot->qtd_atual || $modificacao['valor_unitario_anterior'] != $modificacao['valor_unitario_atual'])
                                                        @php array_push($apropriacoes_id, $apropriacao->id); @endphp
                                                        <tr>
                                                            <td>
                                                                {{ $apropriacao->codigoServico() }}
                                                            </td>
                                                            <td>
                                                                @if($modificacao->anexo)
                                                                    <a href="{!! Storage::url($modificacao->anexo) !!}"
                                                                       target="_blank">Ver</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($apropriacao->pivot->qtd_anterior, '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['valor_unitario_anterior'], '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($apropriacao->pivot->qtd_atual, '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['valor_unitario_atual'], '') }}
                                                            </td>
                                                            <td>
                                                                {{$apropriacao->pivot->descricao}}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="analise-item-{{ $modificacao->id }}" tabindex="-1"
                                 role="dialog">
                                <div class="modal-dialog modal-full" role="document" style="padding-left: 30px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn btn-flat btn-sm btn-danger pull-right" title="Fechar" data-dismiss="modal">
                                                <i class="fa fa-remove fa-fw" aria-hidden="true"></i>
                                            </button>
                                            <h4 class="modal-title">
                                                {{ $modificacao->tipo_modificacao }} <br>
                                                <small>{{ $modificacao->item->insumo->nome }}</small>
                                            </h4>
                                        </div>
                                        <div class="modal-body">
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
                                                @foreach($itens_analise->oc_itens->whereIn('id', $apropriacoes_id) as $item)
                                                    @php
                                                        $qtd_comprometida_a_gastar = $modificacao->apropriacoes->where('id', $item->id)->first()->pivot->qtd_atual;
                                                        $valor_comprometido_a_gastar = $modificacao['valor_unitario_atual'];
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                        <span data-toggle="tooltip" data-placement="right"
                                                              data-html="true"
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
                                                            {{ ($item->qtd_inicial - doubleval($item->qtd_realizada) - doubleval($qtd_comprometida_a_gastar)) < 0
                                                                ? 'red'
                                                                : 'green'
                                                            }}">
                                                            </i>
                                                        </td>
                                                        <td class="text-center">
                                                            {{--CONTA = saldo - previsto no orçamento--}}
                                                            <i class="fa fa-circle
                                                            {{ ($item->preco_inicial - doubleval($item->valor_realizado) - doubleval($valor_comprometido_a_gastar)) < 0
                                                                ? 'red'
                                                                : 'green'
                                                            }}"></i>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($item->servico)
                                                                <a href="/ordens-de-compra/detalhes-servicos/{{$contrato->obra_id}}/{{$item->servico->id}}"
                                                                   style="cursor:pointer;">
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
                                                                                {{float_to_money($qtd_comprometida_a_gastar, '')}}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ number_format( $item->qtd_inicial - doubleval($item->qtd_realizada) - doubleval($qtd_comprometida_a_gastar), 2, ',','.') }}
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
                                                                            <td class="text-center">
                                                                                <small class="pull-left">R$
                                                                                </small> {{ number_format($item->preco_inicial, 2, ',','.') }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <small class="pull-left">R$</small>
                                                                                {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <small class="pull-left">R$</small>
                                                                                {{float_to_money($valor_comprometido_a_gastar, '')}}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <small class="pull-left">R$</small>
                                                                                {{ number_format( $item->preco_inicial - doubleval($item->valor_realizado) - doubleval($valor_comprometido_a_gastar), 2, ',','.') }}
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
                                                                                        <a href="{{ Storage::url($anexo->arquivo) }}"
                                                                                           class="btn btn-default btn-block"
                                                                                           target="_blank">
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
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
