@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Nova Medicão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'medicoes.store']) !!}

                        @include('medicoes.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
