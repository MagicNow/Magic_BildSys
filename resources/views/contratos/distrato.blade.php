<label for="orcamento_item_id">{{ $item->insumo->nome }}</label>
<table class="table table-striped table-no-margin">
  <thead>
    <tr>
      <th>Apropriação</th>
      <th>Saldo de Qtd.</th>
      <th>Quantidade a Distratar</th>
      <th>Nova Quantidade</th>
      <th>Descrição</th>
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
                    value="0,00"
                    data-qtd="{{ $item->qtd_sobra }}"
                    name="distrato[{{ $item->id }}]">
                <span class="input-group-btn">
                    <button class="btn btn-flat btn-warning js-zerar" type="button">
                        Tudo
                    </button>
                </span>
            </div>
        </td>
        <td class="text-right" data-qtd="{{ $item->qtd_sobra }}">
            {{ float_to_money($item->qtd_sobra, '') }}
        </td>
        <td>
            <div class="input-group">
                <textarea class="form-control js-desc" name="distratoDescricao[{{ $item->id }}]"></textarea>
            </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
