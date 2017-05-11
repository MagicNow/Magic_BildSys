@extends('layouts.front')

@section('content')
    <style>
        .element-grafico{
            width: 100%;
            border: solid 1px #dddddd;
        }
        .element-head {
            text-align: center;
            color: #f5f5f5;
            padding: 10px 0px 10px 0px;
            background-color: #474747;
            font-family: Raleway;
            font-weight: bold;
        }
        .element-body{
            min-height: 300px;
            padding: 15px;
            background-color: white;
        }
    </style>
    <section class="content-header">
        <h1 class="pull-left">Quadro De Concorrencias</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary btn-lg btn-flat pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! url('/ordens-de-compra/insumos-aprovados') !!}">
           <i class="fa fa-plus"></i> Criar novo Quadro de Concorrência
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

         {{--Dashboard--}}
        <div class="box-body" id="app">
            <div class="row">
                <div class="col-xs-12">
                    @php
                            $json_data = $json_labels = $json_colors = [];
                            $maximo = 0;
                            foreach ($qcs_por_status as $qcs){
                                $json_data[] = $qcs->qtd;
                                $maximo += $qcs->qtd;
                                $json_labels[] = $qcs->nome;
                                $json_colors[] = $qcs->cor;
                            }
                            $json_data = json_encode($json_data);
                            $json_labels = json_encode($json_labels);
                            $json_colors = json_encode($json_colors);
                    @endphp
                    <div class="row">
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">Por Situação</div>
                                <div class="element-body">
                                    <chartjs-bar :labels="{{$json_labels}}" :data="{{$json_data}}"
                                                 :beginzero="myboolean"
                                                 :backgroundcolor="{{ $json_colors }}"
                                                 :bordercolor="{{ $json_colors }}"
                                                 :option="myoption"
                                                 :height="250"
                                                 :datalabel="'Q.C. por status'"
                                    ></chartjs-bar>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="box box-warning">
            <div class="box-body">
                    @include('quadro_de_concorrencias.table')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        const app = new Vue({
            el: '#app',
            data:{
                myboolean : false,
                myoption: {
                    onClick: function (event, legendItem) {
                        console.log('legendItem',legendItem);
                    },
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                beginAtZero:false,
                                stepSize:1
//                                fixedStepSize: 1
                            }
                        }]
                    },
                    legend: {
                        display: false,
                        position: 'bottom',
                        labels: {
                            boxWidth:10
                        },
                        onClick: function (event, legendItem) {
                            console.log(legendItem);
                        }
                    }
                }
            }
        });
    </script>
@endsection