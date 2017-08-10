<div class="modal fade" id="modal-historico-estruturado-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    Apropriações <br>
                    <small>{{ $item->insumo->nome }}</small>
                </h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-all-center">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="2">Contratado</th>
                            <th colspan="2">Realizado</th>
                            <th colspan="2">Saldo</th>
                            <th colspan="1"></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Apropriação</th>
                            <th>Qtd.</th>
                            <th>Valor Total</th>
                            <th>Qtd.</th>
                            <th>Valor Total</th>
                            <th>Qtd.</th>
                            <th>Valor Total</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->apropriacoes as $apropriacao)
                            <tr>
                                <td>
                                    <button class="btn btn-xs btn-flat btn-default js-collapse"
                                        data-target="#historico-apropriacao-{{ $apropriacao->id }}">
                                        <i class="fa fa-history fa-fw"></i>
                                    </button>
                                    <a href="{{ route('contratos.memoria_de_calculo', [$contrato->id, $apropriacao  ->id]) }}" type="button"
                                       class="btn btn-flat btn-xs btn-primary"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Criar previsão de memória de cálculo">
                                        <i class="fa fa-calculator fa-fw" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>{{ $apropriacao->codigoServico() }}</td>
                                <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                <td>{{ float_to_money($item->valor_unitario * $apropriacao->qtd) }}</td>
                                <td>{{ '0,00' }}</td>
                                <td>{{ '0,00' }}</td>
                                <td>{{ float_to_money($apropriacao->qtd /* - $realizado */, '') }}</td>
                                <td>{{ float_to_money(($item->valor_unitario * $apropriacao->qtd) /* - $realizado */) }}</td>
                                <td>{{ $apropriacao->descricao }}</td>
                            </tr>
                            <tr id="historico-apropriacao-{{ $apropriacao->id }}" class="hidden">
                                <td colspan="8" class="td-full">
                                    @if($apropriacao->modificacoes->where('contrato_status_id', 2)->isNotEmpty())
                                        <table class="table table-condensed table-all-center">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th colspan="2" class="text-center">Antes</th>
                                                    <th colspan="2" class="text-center">Depois</th>
                                                    <th colspan="2"></th>
                                                </tr>
                                                <tr>
                                                    <th>Movimentação</th>
                                                    <th>Qtd.</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Qtd.</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Observação</th>
                                                    <th>Data</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($apropriacao->modificacoes->where('contrato_status_id', 2) as $modificacao)
                                                    <tr>
                                                        <td>
                                                            {{ $modificacao['tipo_modificacao'] }}
                                                        </td>
                                                        <td>
                                                            {{ float_to_money($modificacao->pivot->qtd_anterior, '') }}
                                                        </td>
                                                        <td>
                                                            {{ float_to_money($modificacao['valor_unitario_anterior'], '') }}
                                                        </td>
                                                        <td>
                                                            {{ float_to_money($modificacao->pivot->qtd_atual, '') }}
                                                        </td>
                                                        <td>
                                                            {{ float_to_money($modificacao['valor_unitario_atual'], '') }}
                                                        </td>
                                                        <td>
                                                            {{ $modificacao->pivot->descricao }}
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
