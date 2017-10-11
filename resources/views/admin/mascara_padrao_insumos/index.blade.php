@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h3 class="pull-left title">
            <a href="#" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Insumos da Máscara padrão
        </h3>

        {{--<h1 class="pull-left"><button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>Máscara Padrão / Insumos</h1>--}}

		{{--<h1>--}}
            {{--<a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="{!! route('admin.mascara_padrao_insumos.deleteblocoview') !!}">--}}
                {{--Remover insumos em bloco--}}
            {{--</a>--}}
        {{--</h1>--}}
		{{--<h1>--}}
           {{--<a class="btn btn-warning pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="{!! route('admin.mascara_padrao_insumos.seminsumoview') !!}">--}}
            {{--Ver insumos sem Máscara Padrão--}}
           {{--</a>--}}
        {{--</h1>--}}
		{{--<h1>--}}
           {{--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="{!! route('admin.mascara_padrao_insumos.create') !!}">--}}
            {{--Cadastrar insumos a Máscara Padrão--}}
           {{--</a>--}}
        {{--</h1>--}}
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.mascara_padrao_insumos.table')
            </div>
        </div>
    </div>
@endsection

