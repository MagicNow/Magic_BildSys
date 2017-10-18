@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Tarefa padrão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.tarefa_padrao.store', 'files' => true]) !!}

                        @include('admin.tarefa_padrao.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection



