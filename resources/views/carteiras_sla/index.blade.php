@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Lista de CARTEIRAS e SLA de cada obra</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('carteiras_sla.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('carteiras_sla.table')
            </div>
        </div>
    </div>
@endsection