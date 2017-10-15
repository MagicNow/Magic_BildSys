@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Medições
            <a class="btn btn-primary pull-right"  href="{!! route('medicoes.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('medicoes.table')
            </div>
        </div>
    </div>
@endsection

