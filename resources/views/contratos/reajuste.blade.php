<div class="form-group">
    <label>{{ $item->insumo->nome }}</label>
    @if($item->insumo->unidade_sigla !== 'VB')
    <table class="table table-striped table-no-margin table-condensed">
        <thead>
            <tr>
                <th>Valor Unitário</th>
                <th>Novo Valor Unitário</th>
                <th>Anexar documento</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ float_to_money($item->valor_unitario) }}
                </td>
                <td>
                    <input type="text"
                        class="form-control money js-input js-valor"
                        data-old-value="{{ $item->valor_unitario }}"
                        value="{{ float_to_money($item->valor_unitario, '') }}"
                        name="valor_unitario">
                </td>
                <td>
                    <input class="form-control" type="file" name="anexo">
                </td>
            </tr>
        </tbody>
    </table>
    @endif
</div>
<table class="table table-striped table-no-margin">
  <thead>
    <tr>
      <th>Apropriação</th>
      <th>Saldo de Qtd.</th>
      <th>Quantidadade Adicionada</th>
      <th>Nova Quantidade</th>
      <th>Observação</th>
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
            {{ float_to_money($apropariacao->qtd, '') }}
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
        <td data-item-qtd="{{ $apropariacao->qtd }}">
            {{ float_to_money($apropariacao->qtd, '') }}
        </td>
        <td>
            <div class="input-group">
                <textarea class="form-control js-desc" name="reajusteDescricao[{{ $apropariacao->id }}]"></textarea>
            </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
