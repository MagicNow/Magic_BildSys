@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <a href="/" type="button" class="btn btn-link">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Notas Fiscais
            <a class="btn btn-primary pull-right" href="{!! route('notafiscals.filtro') !!}">Conciliar Nota Fiscal</a>
            <a class="btn btn-primary pull-right" style="margin-right:5px;" href="{!! route('nfe.import') !!}">Importar XML Nota Fiscal</a>
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

