<hr>
<div class="panel panel-default panel-normal-table">
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-all-center">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Descrição do Material</th>
                    <th>Observações ao Fornecedor</th>
                    <th>Preço Unitário</th>
                    <th>Preço Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entrega->itens as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ $item->insumo->unidade_sigla }}</td>
                        <td>{{ $item->insumo->nome }}</td>
                        <td></td>
                        <td>{{ float_to_money($item->valor_unitario) }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

