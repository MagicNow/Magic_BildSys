@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Orçamentos</h1>
        <h1 class="pull-right">
           {{--<a class="btn btn-primary pull-right"  href="{!! route('admin.orcamentos.create') !!}">--}}
            {{--{{ ucfirst( trans('common.new') )}}--}}
           {{--</a>--}}
            <a class="btn btn-primary pull-right" style="margin-top: -10px; margin-bottom: 5px;" href="{!! route('admin.orcamentos.indexImport') !!}">
                Importar Orçamentos
            </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.orcamentos.table')
            </div>
        </div>
    </div>
@endsection

