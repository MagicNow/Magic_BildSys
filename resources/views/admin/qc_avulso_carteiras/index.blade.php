@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <a href="/" type="button" class="btn btn-link">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Carteiras de Q.C. Avulso
            <a class="btn btn-primary pull-right" href="{!! route('admin.qc_avulso_carteiras.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>

    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.qc_avulso_carteiras.table')
            </div>
        </div>
    </div>
@endsection

