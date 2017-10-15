@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pré Orçamentos</h1>
		<a href="{!! route('admin.pre_orcamentos.create') !!}" class="btn btn-lg btn-flat btn-info pull-right"> <i class="fa fa-refresh"></i>  Gerar Pré-Orçamentos</a>
        <h1 class="pull-right">
			{{--<a class="btn btn-primary pull-right"  href="{!! route('admin.pre_orcamentos.create') !!}">--}}
				{{--{{ ucfirst( trans('common.new') )}}--}}
			{{--</a>--}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.pre_orcamentos.table')
            </div>
        </div>
    </div>
@endsection

