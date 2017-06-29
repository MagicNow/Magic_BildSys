<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código Estruturado</th>
            <th>Descrição do Insumo</th>
            <th>Un. Medidia</th>
            <th>Qtd.</th>
            <th>Valor Unitário</th>
            <th style="width: 10%">Solicitação</th>
            <th>Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($apropriacoes as $apropriacao)
            <tr>
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
                <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                <td>{{ float_to_money($apropriacao->contratoItem->valor_unitario) }}</td>
                @if($apropriacao->insumo->codigo === '34007')
                    <td colspan="2">
                        <div class="btn btn-flat btn-block btn-primary">
                            Selecionar Insumo
                        </div>
                    </td>
                @else
                    <td>
                        <input type="text"
                        class="form-control money js-qtd"
                        value"0,00"
                        data-apropriacao="{{ $apropriacao->id }}"
                        data-value-per-unit="{{ $apropriacao->contratoItem->valor_unitario }}"
                        data-qtd-max="{{ $apropriacao->qtd }}">
                    </td>
                    <td class="js-total">
                        R$ 0,00
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
