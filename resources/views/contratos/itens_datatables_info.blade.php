@inject('carbon', 'Carbon\Carbon')

<div class='btn-group'>
  <a href="javascript:void(0)"
      title="{{ $servico }}"
      data-toggle="popover"
      data-container="body"
      data-external-content="#history-table"
      class='btn btn-default btn-xs btn-flat'>
      <i class="fa fa-history fa-fw"></i>
  </a>
</div>

<div id="history-table" class="hidden">
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
      @foreach($modificacoes as $modificacao)
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

