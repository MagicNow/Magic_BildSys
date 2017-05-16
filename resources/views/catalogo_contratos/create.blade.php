@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Catálogo de Acordo
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'catalogo_contratos.store', 'files' => true]) !!}


                        @include('catalogo_contratos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
