@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>Criar QC</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'qc.store']) !!}

                        @include('qc.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
