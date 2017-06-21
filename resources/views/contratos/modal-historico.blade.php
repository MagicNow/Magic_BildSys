<div class="modal fade" id="modal-historico-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    Histórico <br>
                    <small>{{ $item->insumo->nome }}</small>
                </h4>
            </div>
            <div class="modal-body">
                @if($item->modificacoes->isNotEmpty())
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="2" class="text-center">Antes</th>
                                <th colspan="2" class="text-center">Depois</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Movimentação</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->modificacoes as $modificacao)
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
                                        {{ $modificacao['created_at']->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    Item sem modificações
                @endif
            </div>
        </div>
    </div>
</div>
