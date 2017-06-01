<div class="box box-muted">
  <div class="box-header with-border">
    Pendências
  </div>
  <div class="box-body">
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th colspan="2" class="text-center">Antes</th>
          <th colspan="2" class="text-center">Depois</th>
          <th></th>
          <th></th>
        </tr>
        <tr>
          <th>Movimentação</th>
          <th>Código</th>
          <th>Descrição</th>
          <th>Un</th>
          <th>Quantidade</th>
          <th>Valor</th>
          <th>Quantidade</th>
          <th>Valor</th>
          <th>Data</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pendencias as $modificacao)
          <tr>
            <td>{{ $modificacao['tipo_modificacao'] }}</td>
            <td>{{ $modificacao->item->insumo->codigo }}</td>
            <td>{{ $modificacao->item->insumo->nome }}</td>
            <td>{{ $modificacao->item->insumo->unidade_sigla }}</td>
            <td>{{ float_to_money($modificacao['qtd_anterior'], '') }}</td>
            <td>{{ float_to_money($modificacao['valor_unitario_anterior']) }}</td>
            <td>{{ float_to_money($modificacao['qtd_atual'], '') }}</td>
            <td>{{ float_to_money($modificacao['valor_unitario_atual']) }}</td>
            <td>{{ $carbon->parse($modificacao['created_at'])->format('d/m/Y') }}</td>
            <td>
              @if($modificacao->workflow['podeAprovar'])
                @if($modificacao->workflow['iraAprovar'])
                  <div class="btn-group" id="blocoItemAprovaReprovaItem{{ $modificacao->id }}">
                    <button type="button" onclick="workflowAprovaReprova({{ $modificacao->id }},
                      'ContratoItemModificacao',1,'blocoItemAprovaReprovaItem{{ $modificacao->id }}',
                      '{{ $modificacao->tipo_modificacao }}', 0, '', '', true);"
                      class="btn btn-success btn-xs btn-flat"
                      title="Aprovar">
                      Aprovar
                      <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $modificacao->id }},
                      'ContratoItemModificacao',0, 'blocoItemAprovaReprovaItem{{ $modificacao->id }}',
                      '{{ $modificacao->tipo_modificacao }}',0, '', '', true);"
                      class="btn btn-danger btn-xs btn-flat"
                      title="Reprovar Este item">
                      Reprovar
                      <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                  </div>
                @else
                  @if($modificacao->workflow['jaAprovou'])
                    @if($modificacao->workflow['aprovacao'])
                      <span class="btn-xs btn-flat text-success" title="Aprovado por você">
                        <i class="fa fa-check" aria-hidden="true"></i>
                      </span>
                    @else
                      <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                        <i class="fa fa-times" aria-hidden="true"></i>
                      </span>
                    @endif
                  @else
                    <button type="button" title="{{ $modificacao->workflow['msg'] }}"
                      onclick="swal('{{ $modificacao->workflow['msg'] }}','','info');"
                      class="btn btn-default btn-xs btn-flat">
                      <i class="fa fa-info fa-fw" aria-hidden="true"></i>
                    </button>
                  @endif
                @endif
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
