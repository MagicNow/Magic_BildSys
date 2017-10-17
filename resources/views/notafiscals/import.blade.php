@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Importar XML de Nota fiscal eletrônica.
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary" id="nota_fiscal">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'nfe.store', 'files' => true]) !!}
                    <div class="col-md-12">
                        {!! Form::file('nota_fiscal', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12" style="margin-top: 20px;">
                    {!! Form::button( '<i class="fa fa-save"></i> Importar',
                        ['class' => 'btn btn-success pull-right',
                            'type'=>'submit',
                            'value' => 'Importar',
                            'name' => 'importar'
                        ]) !!}
                    {!! Form::close() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
