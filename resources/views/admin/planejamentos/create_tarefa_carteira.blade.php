@extends('layouts.app')

@section('content')
    <style type="text/css">
        #carrinho ul{
            list-style-type: none;
            padding: 0px;
        }
        #carrinho ul li{
            background-color: #ffffff;
            border: solid 1px #dddddd;
            padding: 5px;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 500;
            color: #9b9b9b;
        }
        #carrinho ul li .label-bloco{
            font-size: 13px;
            font-weight: bold;
            color: #4a4a4a;
            line-height: 15px;
            margin-bottom: 0px;
            padding-bottom: 0px;
        }
        #carrinho li button {
            text-align: left !important;
        }
        @media (min-width: 769px){
            .label-bloco-limitado{
                margin-top: -5px;
            }
        }
        @media (min-width: 1215px){
            .margem-botao{
                margin-top: -15px;
            }
        }
        .tooltip-inner {
            max-width: 500px;
            text-align: left !important;
        }
    </style>

    <section class="content-header">
        <h1 class="pull-left">
            Tarefa / Carteiras de Q.C. Avulso
        </h1>
    </section>

    <div class="content">
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.planejamentos.storeTarefaCarteira']) !!}

                    @include('admin.planejamentos.fields-tarefa-carteira')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
