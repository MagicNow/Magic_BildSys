<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th>Un. Medida</th>
            <th>Saldo de Qtd.</th>
            <th>Valor Unitário</th>
            <th style="width: 10%">Solicitação</th>
            <th>Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($apropriacoes as $apropriacao)
            <tr data-view-name="{{ $apropriacao->insumo->codigo === '30019' ? 'direto' : 'contratada' }}">
                <td
                    data-toggle="tooltip"
                    data-container="body"
                    data-placement="right"
                    data-html="true"
                    title="
                        {{ $apropriacao->grupo->codigo.' - '.$apropriacao->grupo->nome }}<br/>
                        {{ $apropriacao->subgrupo1->codigo.' - '.$apropriacao->subgrupo1->nome }}<br/>
                        {{ $apropriacao->subgrupo2->codigo.' - '.$apropriacao->subgrupo2->nome }}<br/>
                        {{ $apropriacao->subgrupo3->codigo.' - '.$apropriacao->subgrupo3->nome }}<br/>
                        {{ $apropriacao->servico->codigo.' - '.$apropriacao->servico->nome }}
                    ">
                    {{ $apropriacao->codigoEstruturado() }}
                </td>
                <td>{{ $apropriacao->insumo->nome }}</td>
                <td>{{ $apropriacao->insumo->unidade_sigla }}</td>
                <td>{{ float_to_money($apropriacao->qtd_saldo, '') }}</td>
                <td>{{ float_to_money($apropriacao->contratoItem->valor_unitario) }}</td>
                <td>
                    @include('contratos.solicitacao_entrega.apropriacao_actions')
                </td>
                <td class="js-total">
                    R$ 0,00
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
