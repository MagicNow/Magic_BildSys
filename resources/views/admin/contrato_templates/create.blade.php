@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contrato Template
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.contratoTemplates.store']) !!}

                        @include('admin.contrato_templates.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
