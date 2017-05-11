@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>Avaliar Quadro de Concorrência </h1>
  </section>
  <div class="content">
    <div class="box box-muted">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-4">
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
            <div class="col-sm-4">
              <div class="box box-muted box-chart">
                <div class="box-header with-border">Insumo / Fornecedor</div>
                <div class="box-body">
                  <div class="form-group">
                    {!!
                      Form::select(
                        'fornecedor',
                        $quadro->itens->pluck('insumo')->flatten()->pluck('nome', 'id')->toArray(),
                        null,
                        ['class' => 'select2 form-control']
                      )
                    !!}
                  </div>
                  <canvas id="chart-insumo-fornecedor"
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
            </div>
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
    @endsection

    @section('scripts')
      <script>
        function pastelColors() {
          var r = (Math.round(Math.random() * 127) + 127).toString(16);
          var g = (Math.round(Math.random() * 127) + 127).toString(16);
          var b = (Math.round(Math.random() * 127) + 127).toString(16);
          return '#' + r + g + b;
        }

        var modal = $('#equalizacao-tecnica');

        $('[data-qcfornecedor]').on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          startLoading();

          $.get('/quadro-de-concorrencia/{{ $quadro->id }}/equalizacao-tecnica/' + this.dataset.qcfornecedor)
            .done(function(html) {
              modal.find('.modal-body').html(html);
              modal.modal('show');
            })
            .error(function() {
              swal('Não encontrado', 'Quadro de Concorrência não econtrado.', 'error');
            })
            .always(function() {
              stopLoading();
            });
        });

        var chartTotalFornecedor  = document.getElementById('chart-total-fornecedor');
        var chartInsumoFornecedor = document.getElementById('chart-insumo-fornecedor');

        chartTotalFornecedor.height = 280;
        chartInsumoFornecedor.height = 230;

        var labels = chartTotalFornecedor.dataset.labels.split('||');
        var values = chartTotalFornecedor.dataset.values.split('||');

        var __chartTotalFornecedor = new Chart(chartTotalFornecedor, {
          type: 'bar',
          data: {
            labels: labels,
              datasets: [{
              label: 'Valor Total',
              data: values,
              backgroundColor: _.times(labels.length, pastelColors),
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: false,
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  return 'Valor total: ' + floatToMoney(tooltipItem.yLabel);
                }
              }
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true
                }
              }]
            }
          }
        });

        var __chartInsumoFornecedor = new Chart(chartInsumoFornecedor, {
          type: 'bar',
          data: {
            labels: labels,
              datasets: [{
              label: 'Valor Total',
              data: values,
              backgroundColor: _.times(labels.length, pastelColors),
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: false,
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  return 'Valor total: ' + floatToMoney(tooltipItem.yLabel);
                }
              }
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true
                }
              }]
            }
          }
        });
      </script>
      {!! $dataTable->scripts() !!}
    @endsection

