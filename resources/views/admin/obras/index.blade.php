@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1> <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Obras

           <a class="btn btn-primary btn-flat btn-lg pull-right"  href="{!! route('admin.obras.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include( 'flash::message' )
                @include('admin.obras.table')
            </div>
        </div>
    </div>
@endsection

