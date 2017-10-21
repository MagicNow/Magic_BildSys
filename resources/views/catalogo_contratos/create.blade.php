@extends('layouts.front')

@section('content')
<style type="text/css">
h2{
    font-size:22px!important;
    font-weight:700!important;
    text-transform:uppercase!important;
}
.ml15{
    margin-top:0!important;
    text-transform:uppercase;
    margin-left:0!important;
}
.modal-header{
    padding:15px 15px 0!important;
    border:0!important;
    margin-bottom: -10px!important;
    display: inline-block;
    margin-top: -32px;
    text-transform:uppercase;
}
</style>
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Catálogo de acordo
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'catalogo_contratos.store', 'files' => true]) !!}


                        @include('catalogo_contratos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
