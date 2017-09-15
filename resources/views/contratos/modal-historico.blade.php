@if(!isset($impressao))
<div class="modal fade" id="modal-historico-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    Histórico de Alteração<br>
                    <small>
                        {{ $item->insumo->codigo .' - '.
                           $item->insumo->nome   .' - '.
                           $item->insumo->unidade_sigla
                        }}
                    </small>
                </h4>
            </div>
            <div class="modal-body">
@endif
                @if($item->modificacoes->where('contrato_status_id', 2)->isNotEmpty())
                    @if(isset($impressao))
                        <h5 style="padding: 0px; margin: 0px;">Históricos de Alterações</h5>
                    @endif
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th colspan="1"></th>
                                <th colspan="2" class="text-center">Antes</th>
                                <th colspan="2" class="text-center">Depois</th>
                                <th colspan="4"></th>
                            </tr>
                            <tr>
                                {{--<th>id</th>--}}
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
                            @foreach($item->modificacoes->where('contrato_status_id', 2) as $modificacao)
                                <tr>
                                    {{--<td>--}}
                                    {{--{{ $modificacao['id'] }}--}}
                                    {{--</td>--}}
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
                    @if(!isset($impressao))
                    Item sem alteração
                    @endif
                @endif
@if(!isset($impressao))
            </div>
        </div>
    </div>
</div>
@endif