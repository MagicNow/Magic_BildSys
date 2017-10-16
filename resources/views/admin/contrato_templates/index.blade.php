@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Templates de Contratos
            <a class="btn btn-primary pull-right"  href="{!! route('admin.contratoTemplates.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.contrato_templates.table')
            </div>
        </div>
    </div>
@endsection

