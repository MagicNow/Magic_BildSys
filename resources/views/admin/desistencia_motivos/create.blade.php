@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivo para declinar proposta
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.desistenciaMotivos.store']) !!}

                        @include('admin.desistencia_motivos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
