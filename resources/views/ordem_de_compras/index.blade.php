@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Listagem de OC</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('ordem_de_compras.table')
            </div>
        </div>
    </div>
@endsection

