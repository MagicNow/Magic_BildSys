@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Mascara Padrao Estrutura
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.mascaraPadraoEstruturas.store']) !!}

                        @include('admin.mascara_padrao_estruturas.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
