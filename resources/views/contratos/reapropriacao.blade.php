<label for="orcamento_item_id">{{ $item->insumo->nome }}</label>
<table class="table table-striped table-no-margin">
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
                        'data-qtd' => $item->qtd_sobra,
                        'class' => 'js-item'
                    ]
                )
            !!}
        </td>
        <td class="text-left">
          <span data-toggle="tooltip"
            title="{{ $item->servico->nome }}"
            data-container="body">
            {{ $item->codigoServico(false) }}
          </span>
        </td>
        <td class="text-right">{{ $item->qtd_sobra_formatted }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
