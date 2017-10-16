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
            height: 300px;
            padding: 15px;
            background-color: white;
        }
    </style>

    <div class="container dash" >
        <section class="content-header">
            <div class="modal-header">
                <div class="col-md-12">
                    <div class="col-md-9">
                        <h3 class="pull-left title">
                            <a href="#" onclick="history.go(-1);">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i></a> Dashboard
                        </h3>
                    </div>
                </div>
            </div>
        </section>
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
                                <div class="element-head">Status dos Qc´s</div>
                                <div class="element-body">
                                    <chartjs-bar :labels="{{$json_labels}}" :data="{{$json_data}}"
                                                 :beginzero="myboolean"
                                                 :backgroundcolor="{{ $json_colors }}"
                                                 :bordercolor="{{ $json_colors }}"
                                                 :option="myoption"
                                                 :height="250"
                                                 :datalabel="'Q.C. por status'">
                                    </chartjs-bar>
                                </div>
                            </div>
                        </div>
                        @php
                        $json_data_sla = [];
                        foreach ($qcs_por_sla as $qcs_sla){
                        $json_data_sla[] = $qcs_sla;
                        }
                        @endphp
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">FAROL SLA</div>
                                <div class="element-body">
                                    <chartjs-pie :labels="labelsFarol"
                                                 :datasets="datasetsFarol"
                                                 :scalesdisplay="false"
                                                 :option="myoption2"
                                                 :height="250">

                                    </chartjs-pie>
                                </div>
                            </div>
                        </div>
                        @php
                        $json_data_usuario = $json_labels_usuario = [];
                        foreach ($qcs_por_usuario as $qcs_usuario){
                        $json_data_usuario[] = $qcs_usuario->qtd;
                        $json_labels_usuario[] = $qcs_usuario->name;
                        }
                        $json_data_usuario = json_encode($json_data_usuario);
                        $json_labels_usuario = json_encode($json_labels_usuario);
                        @endphp
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">QC criada por usuário</div>
                                <div class="element-body">
                                    <chartjs-pie :labels="labelsPorUsuario"
                                                 :datasets="datasetsFarolUsuario"
                                                 :scalesdisplay="false"
                                                 :option="myoptionUsuario"
                                                 :height="250">

                                    </chartjs-pie>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        @php
                        $json_data_media = $json_labels_media = [];
                        foreach ($qcs_por_media as $qcs_media){
                        $json_data_media[] = $qcs_media->dias;
                        $json_labels_media[] = $qcs_media->name;
                        }
                        $json_data_media = json_encode($json_data_media);
                        $json_labels_media = json_encode($json_labels_media);
                        @endphp
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">Média de dias de negociação - Comprador</div>
                                <div class="element-body">
                                    <chartjs-pie :labels="labelsporMedia"
                                                 :datasets="datasetsMedia"
                                                 :scalesdisplay="false"
                                                 :option="myoptionMedia"
                                                 :height="250">
                                 </chartjs-pie>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">Média de dias de negociação - Geral</div>
                                <div class="element-body">
                                    <div class="text-center" style="font-size: 100px; color:orange; margin-top: 50px;">
                                        {{$qcs_por_media_geral->media}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        @shield('quadroDeConcorrencias.view')

        var data = {!! $json_data_usuario !!};
        var labels = {!! $json_labels_usuario !!};

        var dataMedia = {!! $json_data_media !!};
        var labelsMedia = {!! $json_labels_media !!};

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
                                stepSize:1,
                                min: 0
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
                },
                labelsPorUsuario: labels,
                myoptionUsuario: {
                    onClick: function (event, legendItem) {
                        {{--window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;--}}
                    }
                },
                datasetsFarolUsuario:[{
                    data: data,
                    backgroundColor: _.times(labels.length, _.partial(generateFlatColor, undefined))
                }],
                labelsFarol: ["Mais de 30 dias","Passou do prazo","Até 30 dias"],
                myoption2: {
                    onClick: function (event, legendItem) {
                        {{--window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;--}}
                    }
                },
                datasetsFarol:[{
                    data: [{{$json_data_sla[0]->verde}},{{$json_data_sla[0]->vermelho}},{{$json_data_sla[0]->amarelo}}],
                    backgroundColor: ['#00c906','#ff0000','#ffe500']
                }],
                labelsporMedia: labelsMedia,
                myoptionMedia: {
                    onClick: function (event, legendItem) {
                        {{--window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;--}}
                    }
                },
                datasetsMedia:[{
                    data: dataMedia,
                    backgroundColor: _.times(labelsMedia.length, _.partial(generateFlatColor, undefined))
                }]
            }

        });
        @endshield
    </script>
@endsection
