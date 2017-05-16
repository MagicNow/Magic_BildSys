@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Equalização Técnica
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'tipoEqualizacaoTecnicas.store', 'files'=>true]) !!}

                        @include('tipo_equalizacao_tecnicas.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
