@if(!isset($impressao))
<div class="modal fade" id="modal-historico-estruturado-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                   {{ $item->insumo->codigo }} - {{ $item->insumo->nome }} - {{ $item->insumo->unidade_sigla }}
                </h4>
            </div>
            <div class="modal-body">
@endif
                <table class="table table-bordered table-all-center">
                    <thead>
                        <tr>
                            <th colspan="{{ !isset($impressao)?'2':'1' }}"></th>
                            <th colspan="2">Contratado</th>
                            <th colspan="2">Realizado</th>
                            <th colspan="3">Saldo</th>
                            <th colspan="1"></th>
                        </tr>
                        <tr>
                            @if(!isset($impressao))
                            <th></th>
                            @endif
                            <th>Apropriação</th>
                            <th>Qtd.</th>
                            <th>Valor Total</th>
                            <th>Qtd.</th>
                            <th>Valor Total</th>
                            <th>Qtd.</th>
                            <th>Valor Unitário</th>
                            <th>Valor Total</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->apropriacoes as $apropriacao)
                            <tr>
                                @if(!isset($impressao))
                                <td>
                                    <a href="{{ route('contratos.memoria_de_calculo', [$contrato->id, $apropriacao  ->id]) }}" type="button"
                                       class="btn btn-flat btn-xs btn-primary"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Criar previsão de memória de cálculo">
                                        <i class="fa fa-calculator fa-fw" aria-hidden="true"></i>
                                    </a>

                                    <button class="btn btn-xs btn-flat btn-default js-collapse"
                                        data-target="#historico-apropriacao-{{ $apropriacao->id }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Histórico Apropriação">
                                        <i class="fa fa-history fa-fw"></i>
                                    </button>

                                </td>
                                @endif
                                <td>{{ $apropriacao->codigoServico() }}</td>
                                <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                <td>{{ float_to_money($item->valor_unitario * $apropriacao->qtd) }}</td>
                                <td>{{ '0,00' }}</td>
                                <td>{{ '0,00' }}</td>
                                <td>{{ float_to_money($apropriacao->qtd /* - $realizado */, '') }}</td>
                                <td>{{ float_to_money($item->valor_unitario) }}</td>
                                <td>{{ float_to_money(($item->valor_unitario * $apropriacao->qtd) /* - $realizado */) }}</td>
                                <td>{{ $apropriacao->descricao }}</td>
                            </tr>
                            <tr id="historico-apropriacao-{{ $apropriacao->id }}" class="hidden">
                                <td colspan="10" class="td-full">
                                    @if($apropriacao->contratoItem)
                                        @if($apropriacao->contratoItem->modificacoes->where('contrato_status_id', 2)->isNotEmpty())
                                            <table class="table table-condensed table-all-center">
                                                <thead>
                                                    <tr>
                                                        <th colspan="1"></th>
                                                        <th colspan="2" class="text-center">Antes</th>
                                                        <th colspan="2" class="text-center">Depois</th>
                                                        <th colspan="4"></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Alteração</th>
                                                        <th>Qtd.</th>
                                                        <th>Valor Unitário</th>
                                                        <th>Qtd.</th>
                                                        <th>Valor Unitário</th>
                                                        <th>Variação</th>
                                                        <th>Anexo</th>
                                                        <th>Observação</th>
                                                        <th>Data</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($apropriacao->contratoItem->modificacoes->where('contrato_status_id', 2) as $modificacao)
                                                        <tr>
                                                            <td>
                                                                {{ $modificacao['tipo_modificacao'] }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['qtd_anterior'], '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['valor_unitario_anterior'], '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['qtd_atual'], '') }}
                                                            </td>
                                                            <td>
                                                                {{ float_to_money($modificacao['valor_unitario_atual'], '') }}
                                                            </td>
                                                            <td>
                                                                @if($modificacao->tipo_modificacao == \App\Models\ContratoItemModificacao::REAJUSTE_VALOR)
                                                                    {{ float_to_money($modificacao['valor_unitario_atual'] - $modificacao['valor_unitario_anterior']) }}
                                                                @else
                                                                    {{ float_to_money($modificacao['qtd_atual'] -  $modificacao['qtd_anterior'], '') }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($modificacao->anexo)
                                                                    <a href="{!! Storage::url($modificacao->anexo) !!}" target="_blank">Ver</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $modificacao['descricao'] }}
                                                            </td>
                                                            <td>
                                                                {{ $modificacao['created_at']->format('d/m/Y') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Apropriação sem modificações</p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
@if(!isset($impressao))
            </div>
        </div>
    </div>
</div>
@endif
