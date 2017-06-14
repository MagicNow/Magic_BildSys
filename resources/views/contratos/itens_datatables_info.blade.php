@inject('carbon', 'Carbon\Carbon')

<div class='btn-group'>
    @shield('contratos.reapropriar')
        @if($reapropriacoes->isNotEmpty())
            <a href="javascript:void(0)"
                title="Reapropriações"
                data-toggle="popover"
                data-container="body"
                data-external-content="#reapropriacao-{{ $item->id }}"
                class='btn btn-info btn-xs btn-flat'>
                <i class="fa fa-asterisk fa-fw"></i>
            </a>
        @endif
    @endshield
    <a href="javascript:void(0)"
        title="{{ $item->servico }} / {{ $item->insumo->nome }}"
        data-toggle="popover"
        data-container="body"
        data-external-content="#history-table-{{ $item->id }}"
        class='btn btn-default btn-xs btn-flat'>
        <i class="fa fa-history fa-fw"></i>
    </a>
    @if($reprovado)
        <a href="javascript:void(0)"
            title="{{ $item->aprovado
                ? 'Contém modificação reprovada'
                : 'Item não adicionado ao contrato' }}"
            data-toggle="popover"
            data-container="body"
            data-external-content="#reprovado-table-{{ $item->id }}"
            class='btn btn-danger btn-xs btn-flat'>
            <i class="fa fa-ban fa-fw"></i>
        </a>
    @endif
</div>

@if($reprovado)
<div id="reprovado-table-{{ $item->id }}" class="hidden">
  <table class="table table-striped table-condensed">
    <thead>
    <tr>
      <th></th>
      <th colspan="2" class="text-center">Antes</th>
      <th colspan="2" class="text-center">Depois</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
    <tr>
      <th>Movimentação</th>
      <th>Quantidade</th>
      <th>Valor</th>
      <th>Quantidade</th>
      <th>Valor</th>
      <th>Data</th>
      <th>Motivo</th>
      <th>Justificativa</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $reprovado['tipo_modificacao'] }}</td>
        <td>{{ float_to_money($reprovado['qtd_anterior'], '') }}</td>
        <td>{{ float_to_money($reprovado['valor_unitario_anterior'], '') }}</td>
        <td>{{ float_to_money($reprovado['qtd_atual'], '') }}</td>
        <td>{{ float_to_money($reprovado['valor_unitario_atual'], '') }}</td>
        <td>{{ $carbon->parse($reprovado['created_at'])->format('d/m/Y') }}</td>
        <td>{{ $workflow->motivo->nome }}</td>
        <td>{{ $workflow->justificativa }}</td>
      </tr>
    </tbody>
  </table>
</div>
@endif

<div id="reapropriacao-{{ $item->id }}" class="hidden">
    @foreach($apropriacoes as $apropriacao)
        <div class="box box-muted">
            <div class="box-header with-border">
                {{ $apropriacao->codigoServico() }}
                <span class="label label-info label-normalize">
                    Total: {{ $apropriacao->qtd_formatted }}
                </span>
                <span class="label label-warning label-normalize">
                    Sobrou: {{ $apropriacao->qtd_sobra_formatted }}
                </span>
            </div>
            <div class="box-body">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($apropriacao->reapropriacoes as $re)
                            <tr>
                                <td>{{ $re->codigoServico() }}</td>
                                <td>
                                    {{ float_to_money($re->qtd, '') }}
                                    {{ $re->insumo->unidade_sigla }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    @foreach($reapropriacoes as $re)
        @if($re->reapropriacoes->isNotEmpty())
            <div class="box box-muted">
                <div class="box-header with-border">
                    {{ $re->codigoServico() }}
                    <span class="label label-info label-normalize">
                        Total: {{ $re->qtd_formatted }}
                    </span>
                    <span class="label label-warning label-normalize">
                        Sobrou: {{ $re->qtd_sobra_formatted }}
                    </span>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($re->reapropriacoes as $re)
                                <tr>
                                    <td>{{ $re->codigoServico() }}</td>
                                    <td>
                                        {{ float_to_money($re->qtd, '') }}
                                        {{ $re->insumo->unidade_sigla }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>

<div id="history-table-{{ $item->id }}" class="hidden">
    @if($item->modificacoes->isEmpty())
        <p>Não há modificações no item de contrato</p>
    @else
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="2" class="text-center">Antes</th>
                    <th colspan="2" class="text-center">Depois</th>
                    <th></th>
                </tr>
                <tr>
                    <th>Movimentação</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->modificacoes->toArray() as $modificacao)
                    <tr>
                        <td>{{ $modificacao['tipo_modificacao'] }}</td>
                        <td>{{ float_to_money($modificacao['qtd_anterior'], '') }}</td>
                        <td>{{ float_to_money($modificacao['valor_unitario_anterior'], '') }}</td>
                        <td>{{ float_to_money($modificacao['qtd_atual'], '') }}</td>
                        <td>{{ float_to_money($modificacao['valor_unitario_atual'], '') }}</td>
                        <td>{{ $carbon->parse($modificacao['created_at'])->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
