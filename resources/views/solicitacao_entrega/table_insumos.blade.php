@php
    $editing = isset($editing) ? $editing : false;
@endphp

<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código do Insumo</th>
            <th>Descrição do Insumo</th>
            <th>Un. Medidia</th>
            <th>Qtd. Solicitada</th>
            <th>Valor Unitário</th>
            <th>Valor Total</th>
            <th style="width: 10%">Ação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entrega->itens as $item)
            <tr>
                <td>{{ $item->insumo->codigo }}</td>
                <td>{{ $item->insumo->nome }}</td>
                <td>{{ $item->insumo->unidade_sigla }}</td>
                <td>{{ float_to_money($item->qtd, '') }}</td>
                <td>{{ float_to_money($item->valor_unitario) }}</td>
                <td>{{ float_to_money($item->valor_total) }}</td>
                <td>
                    <button type="button"
                         title="Expandir"
                        class="btn btn-flat btn-sm btn-warning"
                        onclick="showHideInfoExtra({{ $item->id }})">
                        <i id="icone-expandir{{ $item->id }}"
                            class="fa fa-caret-right fa-fw">
                        </i>
                    </button>
                </td>
            </tr>
            <tr style="display:none" id="dados-extras{{ $item->id }}">
                <td colspan="7">
                    <table class="table table-bordered table-no-margin table-all-center">
                        <thead>
                            <tr>
                                <th>Código Estruturado</th>
                                <th>Un. Medidia</th>
                                <th>Qtd. Solicitada</th>
                                <th>Nova Qtd. Solicitada</th>
                                <th>
                                    Valor Unitário
                                </th>
                                <th>Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->apropriacoes as $apropriacao)
                                <tr>
                                    <td>
                                        {{ $apropriacao->apropriacao->codigoServico() }}
                                    </td>
                                    <td>{{ $apropriacao->apropriacao->insumo->unidade_sigla }}</td>
                                    <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                    <td>
                                        @if($editing)
                                            <input type="text"
                                                class="form-control money js-qtd"
                                                data-apropriacao="{{ $apropriacao->id }}"
                                                data-item="{{ $item->id }}"
                                                data-value-per-unit="{{ $item->valor_unitario }}"
                                                data-qtd-max="{{ $apropriacao->qtd_saldo }}"
                                                data-initial-value="{{ float_to_money($apropriacao->qtd, '') }}"
                                                value="{{ float_to_money($apropriacao->qtd, '') }}">
                                        @else
                                            {{ float_to_money($apropriacao->qtd, '') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($editing && $item->contratoItem->insumo->is_faturamento_direto)
                                            <input type="text"
                                                data-apropriacao="{{ $apropriacao->id }}"
                                                data-item="{{ $item->id }}"
                                                data-initial-value="{{ float_to_money($item->valor_unitario, '') }}"
                                                class="form-control money js-new-value"
                                                value="{{ float_to_money($item->valor_unitario, '') }}">
                                        @else
                                            {{ float_to_money($item->valor_unitario) }}
                                        @endif
                                    </td>
                                    <td class="js-total">
                                        {{ float_to_money(
                                            $apropriacao->qtd * $item->valor_unitario
                                         ) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
