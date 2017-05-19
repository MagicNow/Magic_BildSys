@extends('layouts.front')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="content-header">
                <h1 class="pull-left">Avaliar Quadro de Concorrência </h1>
                <button class="btn btn-success btn-lg pull-right btn-flat"
                        data-toggle="modal"
                        data-target="#modal-finalizar">
                    <i class="fa fa-trophy" aria-hidden="true"></i>
                    Informar vencedor ou gerar nova rodada
                </button>
            </section>
        </div>
    </div>
    <div class="content">
        <div class="box box-muted">
            <div class="box-body">
                {!!
                  $dataTable->table([
                    'width' => '100%',
                    'class' => 'table table-striped table-hover'
                  ], true)
                !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-muted box-chart">
                            <div class="box-header with-border">Valor Total / Fornecedor</div>
                            <div class="box-body">
                                <canvas id="chart-total-fornecedor"
                                        data-labels="{{
                                            $qcFornecedores
                                              ->pluck('fornecedor')
                                              ->flatten()
                                              ->pluck('nome')
                                              ->implode('||')
                                            }}"
                                        data-values="{{
                                          $qcFornecedores
                                            ->map(function($qcFornecedor) {
                                              return $qcFornecedor->itens->sum('valor_total');
                                            })
                                            ->implode('||')
                                          }}">
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-muted box-chart">
                            <div class="box-header with-border">Insumo / Fornecedor</div>
                            <div class="box-body">
                                <div class="form-group">
                                    {!!
                                      Form::select(
                                        'insumo',
                                        $quadro->itens->pluck('insumo')->flatten()->pluck('nome', 'id')->toArray(),
                                        null,
                                        ['class' => 'select2 form-control', 'id' => 'insumo']
                                      )
                                    !!}
                                </div>
                                <canvas id="chart-insumo-fornecedor"
                                        data-data='{{ json_encode($ofertas) }}'>
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="equalizacao-tecnica" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Equalizações Técnicas</h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div id="modal-finalizar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Quadro de Concorrência</h4>
                </div>
                <div class="modal-body">
                    {!!
                      Form::open([
                        'route' => ['quadroDeConcorrencia.avaliar', $quadro->id],
                        'id' => 'form-finalizar'
                      ])
                    !!}
                    <div class="box box-muted collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Nova Rodada</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body" id="fornecedores-container">
                            <p>Selecione os fornecedores que permanecerão na próxima rodada:</p>
                            @foreach($qcFornecedores as $qcFornecedor)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="fornecedores[]"
                                           value="{{ $qcFornecedor->fornecedor_id }}">
                                    {{ $qcFornecedor->fornecedor->nome }}
                                </label>
                            @endforeach
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-default pull-right btn-sm btn-flat" id="nova-rodada">
                                Gerar nova rodada
                            </button>
                        </div>
                    </div>
                    <div class="box box-muted collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Finalizar Quadro de Concorrência</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-align-middle text-center">
                                <thead>
                                <tr>
                                    <th>Insumos</th>
                                    <th>Quantidade</th>
                                    @foreach($qcFornecedores as $qcFornecedor)
                                        <th>{{ $qcFornecedor->fornecedor->nome }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($quadro->itens as $item)
                                    <tr class="js-insumo-row">
                                        <td>{{ $item->insumo->nome }}</td>
                                        <td>
                                            {{ float_to_money($item->qtd, '') }}
                                            {{ $item->insumo->unidade_sigla }}
                                        </td>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            <th>
                                                <div class="radio">
                                                    <label>
                                                        @php
                                                            $qcItemQcFornecedor = $qcFornecedor->itens
                                                              ->where('qc_item_id', $item->id)
                                                              ->first();
                                                        @endphp
                                                        {{ float_to_money($qcItemQcFornecedor->valor_total) }}
                                                        <br/>
                                                        {!!
                                                          Form::radio(
                                                            "vencedores[{$item->id}]",
                                                            $qcItemQcFornecedor->id
                                                          )
                                                        !!}
                                                    </label>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-default pull-right btn-sm btn-flat" id="finalizar">
                                Finalizar Quadro de Concorrência
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.urlEqualizacao = "/quadro-de-concorrencia/{{ $quadro->id }}/equalizacao-tecnica/";
    </script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection

