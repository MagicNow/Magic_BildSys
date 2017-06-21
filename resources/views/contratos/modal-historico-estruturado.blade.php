<div class="modal fade" id="modal-historico-estruturado-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
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
                @foreach($item->apropriacoes as $apropriacao)
                    <div class="box box-muted">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                {{ $apropriacao->codigoServico() }}
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <table class="table table-bordered table-all-center">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Contratado</th>
                                            </tr>
                                            <tr>
                                                <th>Quantidade</th>
                                                <th>Valor Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                                <td>
                                                    {{ float_to_money($item->valor_unitario * $apropriacao->qtd) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-4">
                                    <table class="table table-bordered table-all-center">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Realizado</th>
                                            </tr>
                                            <tr>
                                                <th>Quantidade</th>
                                                <th>Valor Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ '0,00' }}</td>
                                                <td>
                                                    {{ float_to_money(0) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-4">
                                    <table class="table table-bordered table-all-center">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Saldo</th>
                                            </tr>
                                            <tr>
                                                <th>Quantidade</th>
                                                <th>Valor Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                                <td>
                                                    {{ float_to_money($item->valor_unitario * $apropriacao->qtd) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                                    @foreach($apropriacao->modificacoes as $modificacao)
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
                                                {{ $modificacao['created_at']->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
