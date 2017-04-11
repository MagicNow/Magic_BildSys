@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivo de Reprovação
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.workflow_reprovacao_motivos.show_fields')
                    <a href="{!! route('admin.workflowReprovacaoMotivos.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
