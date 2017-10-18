@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivos de reprovação
            <a class="btn btn-primary pull-right"  href="{!! route('admin.workflowReprovacaoMotivos.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.workflow_reprovacao_motivos.table')
            </div>
        </div>
    </div>
@endsection

