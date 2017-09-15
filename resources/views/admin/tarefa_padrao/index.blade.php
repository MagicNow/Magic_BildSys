@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tarefa Padrão</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.tarefa_padrao.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include( 'flash::message' )
                @include('admin.tarefa_padrao.table')
            </div>
        </div>
    </div>
@endsection

