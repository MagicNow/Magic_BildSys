<label for="orcamento_item_id">{{ $item->insumo->nome }}</label>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Código</th>
      <th>Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($itens as $item)
      <tr>
        <td>
          {!!
            Form::radio(
              'item_id',
              $item->id,
              null,
              [
                'data-column' => class_basename($item) === 'OcItem'
                  ? 'ordem_de_compra_item_id'
                  : 'contrato_item_reapropriacao_id',
                'data-qtd-max' => $item->qtd,
                'class' => 'js-item'
              ]
            )
          !!}
        </td>
        <td data-toggle="tooltip" title="{{ $item->servico->nome }}" data-container="body">{{ $item->codigoServico(false) }}</td>
        <td>{{ float_to_money($item->qtd, '') . ' ' . $item->insumo->unidade_sigla}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
