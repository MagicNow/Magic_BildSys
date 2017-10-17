@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pré orçamento
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.pre_orcamentos.store']) !!}

                        @include('admin.pre_orcamentos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
