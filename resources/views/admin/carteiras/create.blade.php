@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Carteira
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.carteiras.store', 'files' => true]) !!}

                        @include('admin.carteiras.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection



