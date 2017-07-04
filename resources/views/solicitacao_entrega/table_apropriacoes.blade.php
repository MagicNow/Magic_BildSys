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
                        {{ $item->apropriacao->grupo->codigo.' - '.$item->apropriacao->grupo->nome }}<br/>
                        {{ $item->apropriacao->subgrupo1->codigo.' - '.$item->apropriacao->subgrupo1->nome }}<br/>
                        {{ $item->apropriacao->subgrupo2->codigo.' - '.$item->apropriacao->subgrupo2->nome }}<br/>
                        {{ $item->apropriacao->subgrupo3->codigo.' - '.$item->apropriacao->subgrupo3->nome }}<br/>
                        {{ $item->apropriacao->servico->codigo.' - '.$item->apropriacao->servico->nome }}
                    ">
                    {{ $item->apropriacao->codigoEstruturado() }}
                </td>
                <td>{{ $item->solicitacaoEntregaItem->insumo->nome }}</td>
                <td>{{ $item->solicitacaoEntregaItem->insumo->unidade_sigla }}</td>
                <td>{{ float_to_money($item->qtd, '') }}</td>
                <td>{{ float_to_money($item->solicitacaoEntregaItem->valor_unitario) }}</td>
                <td class="js-total">
                    {{ float_to_money($item->qtd * $item->solicitacaoEntregaItem->valor_unitario) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
