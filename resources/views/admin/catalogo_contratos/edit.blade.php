@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Catálogo de Acordo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($catalogoContrato, ['route' => ['admin.catalogo_contratos.update', $catalogoContrato->id], 'method' => 'patch', 'files' => true]) !!}
                        @include('flash::message')

                        @include('admin.catalogo_contratos.fields')
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection