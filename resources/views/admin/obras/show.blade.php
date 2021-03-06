@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Obra
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body for-obras">
                <div class="row" style="padding-left: 20px">
                    @include('admin.obras.show_fields')
                    <div style="clear:both;"></div>
                    <a href="{!! route('admin.obras.index') !!}" class="btn btn-warning btn-lg btn-flat">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
