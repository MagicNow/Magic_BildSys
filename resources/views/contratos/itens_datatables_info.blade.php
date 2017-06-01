@inject('carbon', 'Carbon\Carbon')

<div class='btn-group'>
  <a href="javascript:void(0)"
      title="Reapropriações"
      data-toggle="popover"
      data-container="body"
      data-external-content="#reapropriacao-{{ $item->id }}"
      class='btn btn-info btn-xs btn-flat'>
      <i class="fa fa-asterisk fa-fw"></i>
  </a>
  <a href="javascript:void(0)"
      title="{{ $item->servico }} / {{ $item->insumo->nome }}"
      data-toggle="popover"
      data-container="body"
      data-external-content="#history-table-{{ $item->id }}"
      class='btn btn-default btn-xs btn-flat'>
      <i class="fa fa-history fa-fw"></i>
  </a>
</div>

<div id="reapropriacao-{{ $item->id }}" class="hidden">
    @if($item->qcItem)
        @if($item->qcItem->ordemDeCompraItens->pluck('reapropriacoes')->collapse()->isEmpty())
            <p>Não foram realizadas reapropriações neste item do contrato</p>
        @endif

        @foreach($item->qcItem->ordemDeCompraItens as $ordemDeCompraItem)
          @if($ordemDeCompraItem->reapropriacoes->isNotEmpty())
            <div class="box box-muted">
              <div class="box-header with-border">
                {{ $ordemDeCompraItem->codigoServico() }}
              </div>
              <div class="box-body">
                  <table class="table table-striped table-condensed">
                    <thead>
                      <tr>
                        <th>Serviço</th>
                        <th>Quantidade</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($ordemDeCompraItem->reapropriacoes as $reapropriacao)
                      <tr>
                        <td>{{ $reapropriacao->codigoServico() }}</td>
                        <td>{{ float_to_money($reapropriacao->qtd, '') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
            </div>
          @endif
        @endforeach
    @endif
</div>

<div id="history-table-{{ $item->id }}" class="hidden">
  <table class="table table-striped table-condensed">
    <thead>
      <tr>
        <th></th>
        <th colspan="2" class="text-center">Antes</th>
        <th colspan="2" class="text-center">Depois</th>
        <th></th>
      </tr>
      <tr>
        <th>Movimentação</th>
        <th>Quantidade</th>
        <th>Valor</th>
        <th>Quantidade</th>
        <th>Valor</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
      @foreach($item->modificacoes->toArray() as $modificacao)
      <tr>
        <td>{{ $modificacao['tipo_modificacao'] }}</td>
        <td>{{ float_to_money($modificacao['qtd_anterior'], '') }}</td>
        <td>{{ float_to_money($modificacao['valor_unitario_anterior'], '') }}</td>
        <td>{{ float_to_money($modificacao['qtd_atual'], '') }}</td>
        <td>{{ float_to_money($modificacao['valor_unitario_atual'], '') }}</td>
        <td>{{ $carbon->parse($modificacao['created_at'])->format('d/m/Y') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

