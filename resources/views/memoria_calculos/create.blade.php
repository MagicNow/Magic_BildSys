@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Memória de Cálculo
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'memoriaCalculos.store']) !!}

                        @include('memoria_calculos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
