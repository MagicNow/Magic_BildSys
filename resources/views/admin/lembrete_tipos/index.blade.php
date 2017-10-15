@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipos de Lembretes
            <a class="btn btn-primary pull-right"  href="{!! route('admin.lembreteTipos.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.lembrete_tipos.table')
            </div>
        </div>
    </div>
@endsection

