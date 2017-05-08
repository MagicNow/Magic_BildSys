@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Quadro De Concorrencias</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! url('/ordens-de-compra/insumos-aprovados') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-warning">
            <div class="box-body">
                    @include('quadro_de_concorrencias.table')
            </div>
        </div>
    </div>
@endsection
