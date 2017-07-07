@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Nota fiscal
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'notafiscals.store', 'files' => true]) !!}

                        @include('notafiscals.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
