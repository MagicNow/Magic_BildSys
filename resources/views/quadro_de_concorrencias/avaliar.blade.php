@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>Avaliar Quadro de Concorrência </h1>
  </section>
  <div class="content"> <div class="box box-muted">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-4">
            <canvas id="chart"
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
                    return $qcFornecedor->itens->sum('valor_total') + 0.92;
                  })
                  ->implode('||')
              }}">
            </canvas>
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
@endsection

@section('scripts')
  <script>
    var myChart = new Chart(chart, {
      type: 'bar',
      data: {
        labels: chart.dataset.labels.split('||'),
        height: 200,
        datasets: [{
          label: 'Valor Total',
          data: chart.dataset.values.split('||'),
          backgroundColor: [
            'rgba(255,0,0,1)',
            'rgba(249,141,0,1)',
            'rgba(126, 211, 33,1)'
          ],
        }]
      },
      options: {
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

