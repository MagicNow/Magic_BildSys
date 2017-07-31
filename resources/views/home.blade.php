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
                                <li>
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <small class="label label-danger"><i class="fa fa-clock-o"></i> -1 dias</small>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-warning" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <small class="label label-warning"><i class="fa fa-clock-o"></i> 2 dias</small>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <small class="label label-success"><i class="fa fa-clock-o"></i> 4 dias</small>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <!-- checkbox -->
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <!-- todo text -->
                                    <span class="text">Texto exemplo de tarefa X
                                    <span class="label label-danger"><i class="fa fa-clock-o"></i> 2 dias</span>
                                    </span>
                                    <!-- General tools such as edit or delete-->

                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-warning" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <!-- checkbox -->
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <!-- todo text -->
                                    <span class="text">Texto exemplo de tarefa X
                                    <span class="label label-danger"><i class="fa fa-clock-o"></i> 2 dias</span>
                                    </span>
                                    <!-- General tools such as edit or delete-->

                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-warning" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <!-- checkbox -->
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <!-- todo text -->
                                    <span class="text">Texto exemplo de tarefa X
                                    <span class="label label-danger"><i class="fa fa-clock-o"></i> -3 dias</span>
                                    </span>
                                    <!-- General tools such as edit or delete-->

                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-warning" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <i class="fa fa-exclamation-circle text-success" aria-hidden="true"></i>
                                    <span class="text">Texto exemplo de tarefa X</span>
                                    <a href="#" class="btn btn-flat btn-xs btn-success pull-right">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->

                    </div>
                </div>
            @endif
        </div>
    </div>


@endsection

