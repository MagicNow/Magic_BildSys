@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivo de reprovação
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.workflowReprovacaoMotivos.store']) !!}

                        @include('admin.workflow_reprovacao_motivos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
