@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Lista de Q.C.
            <a class="btn btn-primary pull-right"  href="{!! route('qc.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('qc.table')
            </div>
        </div>
    </div>
@endsection