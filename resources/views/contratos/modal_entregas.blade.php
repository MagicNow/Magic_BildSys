<div class="modal fade" id="modal-entregas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Solicitações de entrega</h4>
            </div>
            <div class="modal-body">
                @if($contrato->entregas->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Solicitante</th>
                                <th>Fornecedor</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Alteração</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contrato->entregas as $entrega)
                                <tr>
                                    <td>{{ $entrega->user->name }}</td>
                                    <td>
                                        {{
                                            $entrega->fornecedor_id
                                                ? $entrega->fornecedor->nome
                                                : $contrato->fornecedor->nome
                                         }}
                                     </td>

                                    <td>{{ $entrega->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ float_to_money($entrega->valor_total) }}</td>
                                    <td>
                                        <i class="fa fa-circle" style="color: {{ $entrega->status->cor }}"></i>
                                        {{ $entrega->status->nome }}
                                    </td>
                                    <td>
                                        <a class="btn btn-flat btn-default btn-xs"
                                            target="_blank"
                                            href="{{ route('solicitacao-entrega.show', $entrega->id) }}"
                                            data-toggle="tooltip"
                                            title="Detalhes">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhuma solicitação de entrega realizada</p>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ route('contratos.solicitar-entrega', $contrato->id) }}" class="btn btn-flat btn-success">
                    Solicitar Entrega
                </a>
            </div>
        </div>
    </div>
</div>
