@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Lista de Anexos do Q.C.</h1>
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