@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Máscara Padrão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.mascara_padrao.store', 'files' => true]) !!}

                        @include('admin.mascara_padrao.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
		
    </div>
@endsection



