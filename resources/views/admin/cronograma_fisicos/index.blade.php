@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cronograma Físicos</h1>
        <h1 class="pull-right">
           {{--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.cronograma_fisicos.create') !!}">--}}
            {{--{{ ucfirst( trans('common.new') )}}--}}
           {{--</a>--}}
            <a class="btn btn-primary pull-right" style="margin-top: -10px; margin-bottom: 5px;" href="{!! route('admin.cronograma_fisicos.indexImport') !!}">
                Importar C. Físico
            </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.cronograma_fisicos.table')
            </div>
        </div>
    </div>
@endsection

