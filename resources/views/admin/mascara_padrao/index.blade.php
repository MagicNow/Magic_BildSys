@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Máscara padrão
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

