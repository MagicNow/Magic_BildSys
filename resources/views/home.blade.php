@extends('layouts.front')

@section('content')
    <style type="text/css">
        .content-wrapper {
            background-image: url("{{ asset('img/backDashboard.jpg') }}") !important;
            background-position: right bottom !important;
            background-repeat: no-repeat !important;
            background-size: contain !important;
            background: #ddd9d8;
        }
        .todo-list{
            max-height: 420px;
            overflow-y: scroll;
        }

        body {
            overflow-y: hidden !important;
        }
    </style>
    <div class="content" id="homeDiv">
        <div class="row">
            @if(auth()->user()->fornecedor)
                <div class="col-sm-3">
                    <a href="{{ route('quadroDeConcorrencias.index') }}" class="info-box">
                        <span class="info-box-icon bg-aqua">
                            <i class="ion-social-usd"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Quadros para preencher</span>
                            <span class="info-box-number">{{ $quadros }}</span>
                        </div>
                    </a>
                </div>
            @else
                <div class="col-md-7 text-center">

                </div>
                <div class="col-md-5">
                    <div class="box box-warning">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">Tarefas</h3>

                            <div class="box-tools pull-right">
                                {{--<ul class="pagination pagination-sm inline">--}}
                                    {{--<li><a href="#">«</a></li>--}}
                                    {{--<li><a href="#">1</a></li>--}}
                                    {{--<li><a href="#">2</a></li>--}}
                                    {{--<li><a href="#">3</a></li>--}}
                                    {{--<li><a href="#">»</a></li>--}}
                                {{--</ul>--}}
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                            <ul class="todo-list">
                                @foreach(auth()->user()->notifications()->whereRaw("data LIKE '%\"task\":1,\"done\":0%'")->get() as $notification)
                                <li>
                                    @php
                                    $prazoWorkflow = isset($workflow_prazos[$notification->data['workflow_tipo_id']]) ?
                                    $workflow_prazos[$notification->data['workflow_tipo_id']] : 1;

                                    $hoje = \Carbon\Carbon::create();
                                    $data_maxima = $notification->created_at->addDays($prazoWorkflow);
                                    $dias_restantes = $hoje->diffInDays($data_maxima, false);
                                    $classe = 'success';
                                    $percentualPrazo = (($dias_restantes * 100) / $prazoWorkflow);
                                    if ($percentualPrazo < 50 && $percentualPrazo > 10) {
                                        $classe = 'warning';
                                    } elseif ($percentualPrazo < 10){
                                        $classe = 'danger';
                                    }
                                    @endphp
                                    <span class="text">{{ $notification->data['message'] }}</span>

                                    <small class="label label-{{ $classe }}"><i class="fa fa-clock-o"></i>
                                        {{ $dias_restantes }} dias
                                    </small>
                                    <a href="{{ $notification->data['link'] }}" class="btn btn-flat btn-xs btn-{{ $classe }} pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.box-body -->

                    </div>
                </div>
            @endif
        </div>
    </div>


@endsection

