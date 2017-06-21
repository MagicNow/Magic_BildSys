<div class="form-group">
    <label>{{ $item->insumo->nome }}</label>
    @if($item->insumo->unidade_sigla !== 'VB')
    <div class="input-group">
        <label class="input-group-addon">Valor Unitário</label>
        <input type="text"
            class="form-control money js-input js-valor"
            data-old-value="{{ $item->valor_unitario }}"
            value="{{ float_to_money($item->valor_unitario, '') }}"
            name="valor_unitario">
    </div>
    @endif
</div>
<table class="table table-striped table-no-margin">
  <thead>
    <tr>
      <th>Código</th>
      <th>Quantidadade Adicionada</th>
      <th>Nova Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($itens as $apropariacao)
      <tr>
        <td class="text-left">
          <span data-toggle="tooltip"
            title="{{ $apropariacao->servico->nome }}"
            data-container="body">
            {{ $apropariacao->codigoServico(false) }}
          </span>
        </td>
        <td>
            <div class="input-group">
                <input type="text"
                    class="form-control money text-right js-input js-adicional"
                    value="0,00"
                    name="reajuste[{{ $apropariacao->id }}]">
                <span class="input-group-addon">
                    {{ $item->insumo->unidade_sigla }}
                </span>
            </div>
        </td>
        <td>
            {{ float_to_money($apropariacao->qtd, '') }}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
