@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left"><button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>Máscara Padrão</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.mascara_padrao.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
	
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">                
                @include('admin.mascara_padrao.table')
            </div>
        </div>
    </div>
@endsection

