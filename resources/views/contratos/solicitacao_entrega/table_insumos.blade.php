<table class="table table-bordered table-no-margin table-all-center">
    <thead>
        <tr>
            <th>Código do Insumo</th>
            <th>Descrição do Insumo</th>
            <th>Un. Medidia</th>
            <th>Qtd.</th>
            <th>Valor Unitário</th>
            <th style="width: 10%">Ação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contrato->itens as $item)
            <tr>
                <td>{{ $item->insumo->codigo }}</td>
                <td>{{ $item->insumo->nome }}</td>
                <td>{{ $item->insumo->unidade_sigla }}</td>
                <td>{{ float_to_money($item->qtd, '') }}</td>
                <td>{{ float_to_money($item->valor_unitario) }}</td>
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
                <td colspan="6">
                    <table class="table table-bordered table-no-margin table-all-center">
                        <thead>
                            <tr>
                                <th>Código Estruturado</th>
                                <th>Un. Medidia</th>
                                <th>Qtd.</th>
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
                                    <td>{{ float_to_money($apropriacao->qtd, '') }}</td>
                                    <td>{{ float_to_money($item->valor_unitario) }}</td>
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
                                            data-value-per-unit="{{ $item->valor_unitario }}"
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
