<label for="orcamento_item_id">{{ $item->insumo->nome }}</label>
<table class="table table-striped table-no-margin">
  <thead>
    <tr>
      <th>#</th>
      <th>Apropriação</th>
      <th>Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($itens as $item_insumo)
      <tr>
        <td>
            {!!
                Form::radio(
                    'item_id',
                    $item_insumo->id,
                    null,
                    [
                        'data-qtd' => $item_insumo->qtd_sobra,
                        'class' => 'js-item'
                    ]
                )
            !!}
        </td>
        <td class="text-left">
            {{ $item_insumo->codigoServico() }}
        </td>
        <td class="text-right">{{ $item_insumo->qtd_sobra_formatted }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<div style="margin-top: 20px; margin-bottom: 20px;">
  <label>
      Informe o destino da apropriação:
  </label>
  @php
        $orcamentos = \App\Http\Controllers\ContratoController::orcamentosReapropriacoes($itens->pluck('insumo_id')->all(), $item->contrato->obra_id);
  @endphp

    {!!
      Form::select(
          'insumo_reapropriado',
          $orcamentos,
          null,
          [
              'id' => 'insumo_reapropriado',
              'class' => 'form-control select2'
          ]
      )
    !!}
</div>

<script>
    $(".select2").select2({
        theme: 'bootstrap',
        placeholder: "-",
        language: "pt-BR",
        allowClear: true
    });
</script>