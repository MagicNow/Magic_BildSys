@php
    $editing = isset($editing) ? $editing : false;
@endphp

<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código Estruturado</th>
            <th>Descrição do Insumo</th>
            <th>Un. Medidia</th>
            <th>Qtd. Solicitada</th>
            <th>Valor Unitário</th>
            <th>Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($apropriacoes as $item)
            <tr>
                <td
                    data-toggle="tooltip"
                    data-container="body"
                    data-placement="right"
                    data-html="true"
                    title="
                        {{ $item->apropriacao->grupo->codigo     .' - '. $item->apropriacao->grupo->nome }}<br/>
                        {{ $item->apropriacao->subgrupo1->codigo .' - '. $item->apropriacao->subgrupo1->nome }}<br/>
                        {{ $item->apropriacao->subgrupo2->codigo .' - '. $item->apropriacao->subgrupo2->nome }}<br/>
                        {{ $item->apropriacao->subgrupo3->codigo .' - '. $item->apropriacao->subgrupo3->nome }}<br/>
                        {{ $item->apropriacao->servico->codigo   .' - '. $item->apropriacao->servico->nome }}
                    ">
                    {{ $item->apropriacao->codigoEstruturado() }}
                </td>
                <td>{{ $item->solicitacaoEntregaItem->insumo->nome }}</td>
                <td>{{ $item->solicitacaoEntregaItem->insumo->unidade_sigla }}</td>
                <td>
                    @if($editing)
                        <input type="text"
                            class="form-control money js-qtd"
                            data-apropriacao="{{ $item->id }}"
                            data-item="{{ $item->solicitacaoEntregaItem->id }}"
                            data-value-per-unit="{{ $item->solicitacaoEntregaItem->valor_unitario }}"
                            data-qtd-max="{{ $item->qtd_saldo }}"
                            data-initial-value="{{ float_to_money($item->qtd, '') }}"
                            value="{{ float_to_money($item->qtd, '') }}">
                    @else
                        {{ float_to_money($item->qtd, '') }}
                    @endif
                </td>
                <td>
                    @if($editing && $item->solicitacaoEntregaItem->contratoItem->insumo->is_faturamento_direto)
                        <input type="text"
                            data-apropriacao="{{ $item->id }}"
                            data-item="{{ $item->solicitacaoEntregaItem->id }}"
                            class="form-control money js-new-value"
                            data-initial-value="{{ float_to_money($item->solicitacaoEntregaItem->valor_unitario, '') }}"
                            value="{{ float_to_money($item->solicitacaoEntregaItem->valor_unitario, '') }}">
                    @else
                        {{ float_to_money($item->solicitacaoEntregaItem->valor_unitario) }}
                    @endif
                </td>
                <td class="js-total">
                    {{ float_to_money($item->qtd * $item->solicitacaoEntregaItem->valor_unitario) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
