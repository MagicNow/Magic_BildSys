@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Nova Medicão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'medicoes.create', 'method'=>'get']) !!}

                    <div class="form-group col-md-6">
                        {!! Form::label('obra_id', 'Obra:') !!}
                        {!! Form::select('obra_id',$obras, null, ['class' => 'form-control select2','required'=>'required']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
