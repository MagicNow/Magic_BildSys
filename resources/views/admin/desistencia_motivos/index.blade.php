@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Motivos para declinar proposta</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right"  href="{!! route('admin.desistenciaMotivos.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.desistencia_motivos.table')
            </div>
        </div>
    </div>
@endsection

