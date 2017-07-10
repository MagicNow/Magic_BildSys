<div class="modal fade" id="modal-impostos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Impostos</h4>
            </div>
            @if(!$isEmAprovacao)
                <div class="modal-body">
                    @if($fornecedor->imposto_simples)
                        <h4>
                            {{ $fornecedor->nome }}
                            <span class="label label-info">ALÍQUOTA SIMPLES</span>
                        </h4>
                        <div class="row">
                            <div class="col-sm-3">
                                <table class="table table-no-margin table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ISS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($iss as $porcentagem)
                                            <tr>
                                                <td>{{ float_to_money($porcentagem, '') }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-9">
                                <table class="table table-no-margin table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Insumo</th>
                                            <th>Inss</th>
                                            <th>Iss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($itens as $item)
                                            <tr>
                                                <td>{{ $item->insumo->nome }}</td>
                                                <td>{{ $item->insumo->cnae->inss ? to_percentage($item->insumo->cnae->inss) : 'Não' }}</td>
                                                <td>{{ $item->insumo->cnae->iss ? to_percentage($item->insumo->cnae->iss) : 'Não' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <h4>
                            {{ $fornecedor->nome }}
                            <span class="label label-info">ALÍQUOTA PRESUMIDA</span>
                        </h4>
                        <table class="table table-no-margin table-bordered">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>ISS</th>
                                    <th>INSS</th>
                                    <th>IRRF</th>
                                    <th>PIS</th>
                                    <th>COFINS</th>
                                    <th>CSLL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itens as $item)
                                    <tr>
                                        <td>{{ $item->insumo->nome }}</td>
                                        @if($item->servico_cnae_id)
                                            <td>{{ to_percentage($item->insumo->cnae->iss) }}</td>
                                            <td>{{ to_percentage($item->insumo->cnae->inss) }}</td>
                                            <td>{{ to_percentage($item->insumo->cnae->irrf) }}</td>
                                            <td>{{ to_percentage($item->insumo->cnae->pis) }}</td>
                                            <td>{{ to_percentage($item->insumo->cnae->cofins) }}</td>
                                            <td>{{ to_percentage($item->insumo->cnae->csll) }}</td>
                                        @else
                                            <td>{{ to_percentage(0) }}</td>
                                            <td>{{ to_percentage(0) }}</td>
                                            <td>{{ to_percentage(0) }}</td>
                                            <td>{{ to_percentage(0) }}</td>
                                            <td>{{ to_percentage(0) }}</td>
                                            <td>{{ to_percentage(0) }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
