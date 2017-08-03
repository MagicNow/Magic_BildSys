@extends('layouts.front')

@section('content')
    @include( 'flash::message' )
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Fornecedores
           <a class="btn btn-primary pull-right btn-lg btn-flat" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.fornecedores.create') !!}">
            {{ ucfirst( trans('common.new') ) }}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="clearfix"></div>

        <div class="box box-primary">
          <div class="box-body">
            @include('admin.fornecedores.table')
          </div>
        </div>
    </div>
@endsection

