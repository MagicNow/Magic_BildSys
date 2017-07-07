@if($apropriacao->insumo->codigo === '34007' || $apropriacao->insumo->codigo === '30019')
    <button
        type="button"
        data-apropriacao="{{ $apropriacao->id }}"
        data-contrato-item="{{ $apropriacao->contrato_item_id }}"
        data-valor-max="{{ $apropriacao->qtd_saldo }}"
        class="btn btn-flat btn-sm btn-block btn-primary js-selecionar-insumo">
        Selecionar Insumos
    </button>
    <div data-apropriacao="{{ $apropriacao->id }}"
        data-contrato-item="{{ $apropriacao->contrato_item_id }}"
        class="hidden js-selected"></div>
@else
    <input type="text"
        class="form-control money js-qtd"
        value"0,00"
        data-apropriacao="{{ $apropriacao->id }}"
        data-contrato-item="{{ $apropriacao->contrato_item_id }}"
        data-value-per-unit="{{ $apropriacao->contratoItem->valor_unitario }}"
        data-qtd-max="{{ $apropriacao->qtd_saldo }}">
@endif
