<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código do Insumo</th>
            <th>Descrição do Insumo</th>
            <th>Un. Medidia</th>
            <th>Saldo de Qtd.</th>
            <th>Valor Unitário</th>
            <th style="width: 10%">Alteração</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contrato->materiais as $item)
            <tr data-view-name="{{ $item->insumo->codigo === '30019' ? 'direto' : 'contratada' }}">
                <td>{{ $item->insumo->codigo }}</td>
                <td>{{ $item->insumo->nome }}</td>
                <td>{{ $item->insumo->unidade_sigla }}</td>
                <td>{{ float_to_money($item->qtd_saldo, '') }}</td>
                <td>{{ float_to_money($item->valor_unitario) }}</td>
                <td>
                    <button type="button"
                         title="Expandir"
                        class="btn btn-flat btn-sm btn-warning js-extra-info"
                        onclick="showHideInfoExtra({{ $item->id }})">
                        <i id="icone-expandir{{ $item->id }}"
                            class="fa fa-caret-right fa-fw">
                        </i>
                    </button>
                </td>
            </tr>
            <tr style="display:none" id="dados-extras{{ $item->id }}">
                <td colspan="6">
                    <table class="table table-bordered table-no-margin table-all-center">
                        <thead>
                            <tr>
                                <th>Código Estruturado</th>
                                <th>Un. Medidia</th>
                                <th>Saldo de Qtd.</th>
                                <th>Valor Unitário</th>
                                <th style="width: 10%">Solicitação</th>
                                <th>Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->apropriacoes as $apropriacao)
                                <tr>
                                    <td>
                                        {{ $apropriacao->codigoServico() }}
                                    </td>
                                    <td>{{ $apropriacao->insumo->unidade_sigla }}</td>
                                    <td>{{ float_to_money($apropriacao->qtd_saldo, '') }}</td>
                                    <td>{{ float_to_money($item->valor_unitario) }}</td>
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
