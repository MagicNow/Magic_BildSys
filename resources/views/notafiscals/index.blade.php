@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Notas fiscais</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" href="{!! route('notafiscals.filtro') !!}">Conciliar Nota Fiscal</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('notafiscals.table')
            </div>
        </div>
    </div>
@endsection

