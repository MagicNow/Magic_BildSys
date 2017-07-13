@extends('layouts.front')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <section class="content-header">
            <h1 class="pull-left">
                <button type="button" class="btn btn-link" onclick="history.go(-1);">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
              Avaliar Quadro de Concorrência
              <small>
                Rodada {{ $rodadaSelecionada }}
              </small>
            </h1>
            <button class="btn btn-success btn-lg pull-right btn-flat"
              data-toggle="modal"
              data-target="#modal-finalizar">
              <i class="fa fa-trophy" aria-hidden="true"></i>
              Informar vencedor
                ou
                <i class="fa fa-refresh" aria-hidden="true"></i>
                Gerar nova rodada
            </button>
          </section>
        </div>
    </div>
    <div class="content">
        <div class="box box-muted">
          <div class="box-header with-border">Exibir rodada</div>
          <div class="box-body">
            @foreach(range(1, $quadro->rodada_atual) as $rodada)
              <label class="radio-inline">
                {!!
                  Form::radio(
                    'rodada',
                    $rodada, $rodada === $rodadaSelecionada,
                    [ 'class' => 'js-change-round' ]
                  )
                !!}
                Rodada {{ $rodada }} {{ $rodada === $quadro->rodada_atual ? '(atual)' : '' }}
              </label>
            @endforeach
          </div>
        </div>
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
                              <div class="box-input">
                                  {!!
                                    Form::select(
                                      'insumo',
                                      $quadro->itens->pluck('insumo')->flatten()->pluck('nome', 'id')->toArray(),
                                      null,
                                      ['class' => 'select2 form-control', 'id' => 'insumo']
                                    )
                                  !!}
                                </div>
                              <div class="box-body">
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
                    <div class="box box-warning collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <button type="button" class="btn btn-warning btn-flat btn-flat btn-lg" data-widget="collapse">
                                    Nova Rodada
                                </button>
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body" id="fornecedores-container">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('qcFornecedores', 'Fornecedores:') !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('fornecedores', ['' => 'Escolha...'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'id'=>'fornecedor'
                                    ]) !!}
                                </div>
                                <div class="col-md-12">
                                    <button type="button" title="Cadastrar Fornecedor Temporariamente"
                                            style="margin-top: 5px; margin-bottom: 5px;"
                                            id="cadastrarFornecedorTemporariamente"
                                            onclick="cadastraFornecedor()" class="btn btn-block btn-sm btn-flat btn-info">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        Cadastrar Temporariamente
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('fornecedores_temp', ['' => 'Fornecedores Temporários...'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'id'=>'fornecedor_temp'
                                    ]) !!}
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <p>Selecione os fornecedores que permanecerão na próxima rodada:</p>
                                @foreach($qcFornecedores as $qcFornecedor)
                                    <div>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="fornecedores[]"
                                                   value="{{ $qcFornecedor->fornecedor_id }}">
                                            {{ $qcFornecedor->fornecedor->nome }}
                                        </label>
                                    </div>

                                    <?php
                                    $qcFornecedorCount = $qcFornecedor->id;
                                    ?>
                                @endforeach
                                <div id="fornecedoresSelecionados"></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-warning pull-right btn-lg btn-flat" id="nova-rodada">
                                <i class="fa fa-refresh" aria-hidden="true"></i> Gerar nova rodada
                            </button>
                        </div>
                    </div>
                    <div class="box box-success collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <button type="button" class="btn btn-success btn-lg btn-flat" data-widget="collapse">
                                    Finalizar Quadro de Concorrência
                                </button>
                            </h3>
                            <div class="box-tools pull-right">

                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-align-middle">
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
                                        <td class="text-left">{{ $item->insumo->nome }}</td>
                                        <td>
                                            {{ float_to_money($item->qtd, '') }}
                                            {{ $item->insumo->unidade_sigla }}
                                        </td>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            <th class="text-center">
                                                <div class="radio">
                                                    <label>
                                                        @php
                                                            $qcItemQcFornecedor = $qcFornecedor->itens
                                                              ->where('qc_item_id', $item->id)
                                                              ->first();
                                                        @endphp
                                                        @if(isset($qcItemQcFornecedor->valor_total))
                                                        {{ float_to_money($qcItemQcFornecedor->valor_total) }}
                                                        <br/>

                                                        {!!
                                                          Form::radio(
                                                            "vencedores[{$item->id}]",
                                                            $qcItemQcFornecedor->id
                                                          )
                                                        !!}
                                                        @else
                                                        Sem proposta
                                                        @endif
                                                    </label>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @if($quadro->hasMaterial())
                                <tr>
                                    <td colspan="2" class="text-right"> <strong>Frete</strong></td>
                                    @foreach($qcFornecedores as $qcFornecedor)
                                        <td class="text-center">
                                            {{ $qcFornecedor->tipo_frete }}
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text"
                                                       class="form-control money"
                                                       value="{{ $qcFornecedor->valor_frete }}"
                                                       name="valor_frete[{{$qcFornecedor->id}}]">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success pull-right btn-lg btn-flat" id="finalizar">
                                <i class="fa fa-trophy" aria-hidden="true"></i>
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
    <script type="text/javascript">
        window.urlEqualizacao = "/quadro-de-concorrencia/{{ $quadro->id }}/equalizacao-tecnica/";

        var qtdFornecedores = parseInt({!! $qcFornecedorCount !!});
        $(function() {
            $('#fornecedor').select2({
                allowClear: true,
                placeholder: "-",
                language: "pt-BR",
                ajax: {
                    url: "/catalogo-acordos/buscar/busca_fornecedores",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },

                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page

            });

            $('#fornecedor_temp').select2({
                allowClear: true,
                placeholder: "Fornecedores Temporários",
                language: "pt-BR",
                ajax: {
                    url: "/admin/fornecedores/busca-temporarios",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },

                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResultNomeId, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelectionNomeId // omitted for brevity, see the source of this page

            });

            $('#fornecedor').on('select2:select', function (e) {
                addFornecedor()
            });

            $('#fornecedor_temp').on('select2:select', function (e) {
                addFornecedorTemp();
            });
        });

        // Fornecedor
        function cadastraFornecedor() {
            funcaoPosCreate = "preencheFornecedor();";
            $.colorbox({
                href: "/admin/fornecedores/create?modal=1",
                iframe: true,
                width: '90%',
                height: '90%'
            });
        }

        function preencheFornecedor() {
            qtdFornecedores++;
            var nomeFornecedor = novoObjeto.nome;
            var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                    '<input type="checkbox" name="qcFornecedores[][fornecedor_id]" value="' + novoObjeto.id + '">' +
                    nomeFornecedor +
                    '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                    ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                    '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                    '</label></div>';

            $('#fornecedor').val(null).trigger("change");
            $('#fornecedoresSelecionados').append(qcFornecedorHTML);

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function addFornecedor() {
            qtdFornecedores++;
            if ($('#fornecedor').val()) {
                var nomeFornecedor = $('#fornecedor').select2('data');
                var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                        '<input type="checkbox" name="qcFornecedoresMega[]" value="' + $('#fornecedor').val() + '"> ' +
                        nomeFornecedor[0].agn_st_nome +
                        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '</label></div>';

                $('#fornecedor').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor').select2('open');
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function addFornecedorTemp() {
            qtdFornecedores++;
            if ($('#fornecedor_temp').val()) {
                var nomeFornecedor = $('#fornecedor_temp').select2('data');
                var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                        '<input type="checkbox" name="qcFornecedores[][fornecedor_id]" value="' + $('#fornecedor_temp').val() + '"> ' +
                        nomeFornecedor[0].nome +
                        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '</label></div>';

                $('#fornecedor_temp').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor_temp').select2('open');
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function removerFornecedor(qual, qcFornecedorId) {
            if (qcFornecedorId) {
                // Remover no banco
                swal({
                    title: 'Deseja remover este fornecedor?',
                    text: 'Após a remoção não será possível mais recuperar o registro.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, tenho certeza!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false
                }, function() {
                    $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/remover-fornecedor/" + qual)
                            .done(function(retorno) {
                                $('#qcFornecedor_id' + qual).remove();
                                swal('Removido', '', 'success');
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                        swal('Erro', jqXHR.responseText, 'error');
                    });
                });
            } else {
                // Apenas remove o HTML
                $('#qcFornecedor_id' + qual).remove();
            }
        }

        function formatResult(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelection(obj) {
            if (obj.agn_st_nome) {
                return obj.agn_st_nome;
            }
            return obj.text;
        }

        function formatResultNomeId(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelectionNomeId(obj) {
            if (obj.nome) {
                return obj.nome;
            }
            return obj.text;
        }

    </script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection

