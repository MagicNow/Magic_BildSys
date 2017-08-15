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

    <div class="container">
        <section class="content-header">
            <div class="modal-header">
                <div class="col-md-12">
                    <div class="col-md-9">
                        <h3 class="pull-left title">
                            <a href="#" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Dashboard
                        </h3>
                    </div>
                </div>
            </div>
        </section>

        <div class="form-group col-sm-12">
            {!! Form::label('obra_id', 'Obra:') !!}
            {!! Form::select('obra_id', $obras, \Illuminate\Support\Facades\Input::get('obra_id'), ['class' => 'form-control select2', 'onchange' => 'filters();']) !!}
        </div>

        <div class="box-body" id="app">
            <div class="row">
                <div class="col-xs-12">
                    @php
                        $json_data = json_encode([$reprovados->count(), $emaprovacao->count(), $aprovados->count()]);
                        //$json_data = json_encode([10,20, 30]);
                        $json_labels = json_encode(['Reprovados', 'Em Aprovação', 'Aprovados']);
                    @endphp
                    <div class="row">
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">ORDENS DE COMPRA</div>
                                <div class="element-body">
                                    <chartjs-bar :datalabel="mylabel"
                                                 :labels="{{$json_labels}}" :data="{{$json_data}}"
                                                 :beginzero="myboolean"
                                                 :backgroundcolor="mybackgroundcolor"
                                                 :bordercolor="mybordercolor"
                                                 :option="myoption"
                                                 :height="250"
                                    ></chartjs-bar>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">RANKING CATEGORIAS REPROVADAS</div>
                                <div class="element-body">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="element-grafico">
                                <div class="element-head">FAROL</div>
                                <div class="element-body">
                                    <chartjs-pie :labels="labelsFarol" :datasets="datasetsFarol" :scalesdisplay="false" :option="myoption2" :height="250"></chartjs-pie>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4"><tile title-color="head-grey" title="Últimas Criadas" type="created"></tile></div>
                        <div class="col-md-4"><tile title-color="head-red" title="Últimas Reprovadas" type="4"></tile></div>
                        <div class="col-md-4"><tile title-color="head-green" title="Últimas Aprovadas" type="5"></tile></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function filters() {
            window.location = '/compras/dashboard?obra_id=' + $('#obra_id').val();
        }

        const app = new Vue({
            el: '#app',
            data:{
                mylabel : 'quantidade',
                myboolean : true,
                mybackgroundcolor : ['rgba(255,0,0,1)','rgba(249,141,0,1)','rgba(126, 211, 33,1)'],
                mybordercolor : ['rgba(255,0,0,1)','rgba(249,141,0,1)','rgba(126, 211, 33,1)'],
                myoption: {
                    onClick: function (event, legendItem) {
                        if(legendItem[0]._index == 0){
                            window.location.href = "{{url('ordens-de-compra?oc_status_id=4')}}";
                        }
                        if(legendItem[0]._index == 1){
                            window.location.href = "{{url('ordens-de-compra?oc_status_id=3')}}";
                        }
                        if(legendItem[0]._index == 2){
                            window.location.href = "{{url('ordens-de-compra?oc_status_id=5')}}";
                        }
                    },
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                beginAtZero:true,
                                fixedStepSize: 10
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

                labelsFarol: ["Dentro do orçamento", "Acima do orçamento"],
                myoption2: {
                    onClick: function (event, legendItem) {
                        window.location.href = "{{url('ordens-de-compra?status_oc=')}}"+legendItem[0]._index;
                    }
                },
                datasetsFarol:[{
                    data: [{{$dentro_orcamento}}, {{$acima_orcamento}}],
                    backgroundColor: [
                        "#7ed321",
                        "#eb0000"
                    ],
                    hoverBackgroundColor: [
                        "#7ed321",
                        "#eb0000"
                    ]
                }]
            }
        });
    </script>
@endsection