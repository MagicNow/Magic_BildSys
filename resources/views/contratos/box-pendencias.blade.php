<div class="box box-muted">
  <div class="box-header with-border">
    Pendências
  </div>
  <div class="box-body">
    <div class="table-responsive">
        <table class="table table-condensed table-all-center table-bordered table-no-margin">
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
              <th>Qtd.</th>
              <th>Valor Unitário</th>
              <th>Qtd.</th>
              <th>Valor Unitário</th>
              <th>Data</th>
              <th style="width: 20%">Ações</th>
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
                <td>{{ $modificacao['created_at']->format('d/m/Y') }}</td>
                <td>
                  @if($modificacao->workflow['podeAprovar'])
                    @if($modificacao->workflow['iraAprovar'])
                      <span id="blocoItemAprovaReprovaItem{{ $modificacao->id }}">
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
                      </span>
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
                  <button class="btn btn-xs btn-info btn-flat"
                      data-toggle="modal"
                      data-target="#detalhes-item-{{ $modificacao->id }}">
                      <span data-toggle="tooltip" title="Detalhes por Apropriação">
                          Detalhes <i class="fa fa-plus fa-fw"></i>
                      </span>
                  </button>
                  <div class="modal fade" id="detalhes-item-{{ $modificacao->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          <h4 class="modal-title">
                              {{ $modificacao->tipo_modificacao }} <br>
                              <small>{{ $modificacao->item->insumo->nome }}</small>
                          </h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-condensed table-all-center table-bordered table-no-margin">
                                <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="2" class="text-center">Antes</th>
                                        <th colspan="3" class="text-center">Depois</th>
                                    </tr>
                                    <tr>
                                        <th>Código Estruturado</th>
                                        <th>Anexo</th>
                                        <th>Qtd.</th>
                                        <th>Valor Unitário</th>
                                        <th>Qtd.</th>
                                        <th>Valor Unitário</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modificacao->apropriacoes as $apropriacao)
                                        <tr>
                                            <td>
                                                {{ $apropriacao->codigoServico() }}
                                            </td>
                                            <td>
                                                @if($modificacao->anexo)
                                                    <a href="{!! Storage::url($modificacao->anexo) !!}" target="_blank">Ver</a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ float_to_money($apropriacao->pivot->qtd_anterior, '') }}
                                            </td>
                                            <td>
                                                {{ float_to_money($modificacao['valor_unitario_anterior'], '') }}
                                            </td>
                                            <td>
                                                {{ float_to_money($apropriacao->pivot->qtd_atual, '') }}
                                            </td>
                                            <td>
                                                {{ float_to_money($modificacao['valor_unitario_atual'], '') }}
                                            </td>
                                            <td>
                                                {{$apropriacao->pivot->descricao}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
  </div>
</div>
