@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            Máscara Padrão
            <a class="btn btn-primary pull-right"  href="{!! route('admin.mascara_padrao.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    @include('flash::message')
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">                
                @include('admin.mascara_padrao.table')
            </div>
        </div>
    </div>
@endsection

