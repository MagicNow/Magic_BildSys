@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo de Levantamentos
           <a class="btn btn-primary pull-right btn-flat btn-lg"  href="{!! route('admin.tipo_levantamentos.create') !!}">
               <i class="fa fa-plus" aria-hidden="true"></i> {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.tipo_levantamentos.table')
            </div>
        </div>
    </div>
@endsection

