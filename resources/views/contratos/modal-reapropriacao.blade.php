<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Código</th>
      <th>Insumo</th>
      <th>Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($itens as $item)
      <tr>
        <td>
          {!!
            Form::radio(
              'ordem_de_compra_item_id',
              $item->id,
              null,
              [
                'data-qtd-max' => $item->qtd,
                'class' => 'js-ordem-de-compra-id'
              ]
            )
          !!}
        </td>
        <td>{{ $item->codigo_insumo }}</td>
        <td>{{ $item->insumo->nome }}</td>
        <td>{{ $item->qtd . ' ' . $item->insumo->unidade_sigla}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
