@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Máscara Padrão
            <a class="btn btn-primary pull-right btn-flat btn-lg" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('mascara_padrao.create') !!}">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ ucfirst( trans('common.new') )}}
            </a>
        </h1>
    </section>
	
    <div class="content">        
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('mascara_padrao.table')
            </div>
        </div>
    </div>
@endsection

