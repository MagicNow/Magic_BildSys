@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Configurações
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'configuracaoEstaticas.store']) !!}

                        @include('configuracao_estaticas.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
