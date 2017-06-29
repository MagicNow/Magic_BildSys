<hr>
<div class="row" id="totalInsumos">
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Inicial</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ float_to_money($contrato->valor_total_inicial, '') }}
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Atual</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ float_to_money($contrato->valor_total_atual, '') }}
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Medido</h5>
        <h4>
            <small class="pull-left">R$</small>
            0,00
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Saldo</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ float_to_money($contrato->valor_total_atual, '') }}
        </h4>
    </div>
</div>

<div class="panel panel-default panel-normal-table">
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-all-center">
            <thead>
                <tr>
                    <th colspan="4"></th>
                    <th colspan="2">Contratado</th>
                    <th colspan="2">Realizado</th>
                    <th colspan="2">Saldo</th>
                    @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */)
                        <th></th>
                    @endif
                </tr>
                <tr>
                    <th>#</th>
                    <th>Código do insumo</th>
                    <th>Descrição do insumo</th>
                    <th>Un. de medida</th>
                    <th>Qtd.</th>
                    <th>Valor Total</th>
                    <th>Qtd.</th>
                    <th>Valor Total</th>
                    <th>Qtd.</th>
                    <th>Valor Total</th>
                    @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */)
                        <th style="width:18%">Ação</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($itens as $item)
                    <tr>
                        <td>
                            <button class="btn btn-primary btn-xs btn-flat"
                                data-toggle="modal"
                                data-target="#modal-historico-estruturado-{{ $item->id }}">
                                <i data-toggle="tooltip"
                                    title="Apropriações"
                                    class="fa fa-fw fa-building"></i>
                            </button>
                            <button class="btn btn-flat btn-xs btn-default"
                                data-toggle="modal"
                                data-target="#modal-historico-{{ $item->id }}">
                                <i data-toggle="tooltip"
                                    title="Histórico"
                                    class="fa fa-fw fa-history"></i>
                            </button>
                            @include('contratos.modal-historico', ['item' => $item])
                            @include('contratos.modal-historico-estruturado', ['item' => $item])
                        </td>
                        <td>{{ $item->insumo->codigo }}</td>
                        <td>{{ $item->insumo->nome }}</td>
                        <td>{{ $item->insumo->unidade_sigla }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                        <td>{{ '0,00' }}</td>
                        <td>{{ 'R$ 0,00' }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                        @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */)
                            <td>
                                @include('contratos.itens_datatables_action', [
                                    'item' => $item
                                ])
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

