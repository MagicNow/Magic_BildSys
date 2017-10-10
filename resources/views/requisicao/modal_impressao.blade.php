@if ($request->query('tem_comodo') == 'true')

    <a href="/requisicao/impressao-qrcode?requisicao_id={{ $insumos[0]->requisicao_id }}&estoque_id={{ $insumos[0]->estoque_id }}&all=true" target="_blank" class="btn btn-primary center-block">
        <i class="fa fa-print" aria-hidden="true"></i>
        Imprimir Todos
    </a>

    <br>

    <table id="insumos-table" class="table table-striped table-responsive">
        <thead>
        <tr align="left">
            <th width="30%">Apartamento</th>
            <th width="12%">Cômodo</th>
            <th width="12%">Requisitado</th>
            <th width="18%">Impressão</th>
        </tr>
        </thead>

        <tbody>

        @foreach($insumos as $insumo)

            <tr>
                <td>{{ $insumo->apartamento }}</td>
                <td>{{ $insumo->comodo }}</td>
                <td>{{ $insumo->qtde }}</td>
                <td>
                    <a href="/requisicao/impressao-qrcode?id={{ $insumo->id }}&requisicao_id={{ $insumo->requisicao_id }}&estoque_id={{ $insumo->estoque_id }}&all=false" target="_blank" class="btn btn-primary">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Imprimir
                    </a>
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>

@else

    <table id="insumos-table" class="table table-striped table-responsive">
        <thead>
        <tr align="left">
            <th width="30%">Insumo</th>
            <th width="12%">Requisitado</th>
        </tr>
        </thead>

        <tbody>

        @foreach($insumos as $insumo)

            <tr>
                <td>{{ $insumo->insumo }}</td>
                <td>{{ $insumo->qtde }}</td>
            </tr>

        @endforeach

        </tbody>
    </table>

@endif