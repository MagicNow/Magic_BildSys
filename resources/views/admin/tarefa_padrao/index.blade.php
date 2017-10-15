@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left"><button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>Tarefa Padrão</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right"  href="{!! route('admin.tarefa_padrao.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">                
                @include('admin.tarefa_padrao.table')
            </div>
        </div>
    </div>
@endsection

