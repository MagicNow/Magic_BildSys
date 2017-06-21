<label for="orcamento_item_id">{{ $item->insumo->nome }}</label>
<table class="table table-striped table-no-margin">
  <thead>
    <tr>
      <th>Código</th>
      <th>Quantidade</th>
      <th>Nova Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($itens as $item)
      <tr>
        <td class="text-left">
          <span data-toggle="tooltip"
            title="{{ $item->servico->nome }}"
            data-container="body">
            {{ $item->codigoServico(false) }}
          </span>
        </td>
        <td class="text-right">{{ $item->qtd_sobra_formatted }}</td>
        <td>
            <div class="input-group">
                <input type="text"
                    class="form-control money text-right js-input"
                    data-old-value="{{ $item->qtd }}"
                    value="{{ float_to_money($item->qtd, '') }}"
                    name="distrato[{{ $item->id }}]">
                <span class="input-group-btn">
                    <button class="btn btn-flat btn-warning js-zerar" type="button">Zerar</button>
                </span>
            </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
