@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pré Orçamentos</h1>
		<a href="{{ url('/pre_orcamentos') }}" class="btn btn-lg btn-flat btn-info pull-right"> <i class="fa fa-refresh"></i>  Gerar Pré-Orçamentos</a>
        <h1 class="pull-right">
			{{--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.pre_orcamentos.create') !!}">--}}
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

