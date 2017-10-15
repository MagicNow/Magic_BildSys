@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Alçadas</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right"  href="{!! route('admin.workflowAlcadas.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include( 'flash::message' )
                    @include('admin.workflow_alcadas.table')
            </div>
        </div>
    </div>
@endsection

