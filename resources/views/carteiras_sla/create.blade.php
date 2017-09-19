@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>Lista de CARTEIRAS e SLA de cada obra</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'carteiras_sla.store']) !!}

                        @include('carteiras_sla.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
