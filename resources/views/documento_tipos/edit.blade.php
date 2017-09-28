@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Documento Tipo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($documentoTipo, ['route' => ['documentoTipos.update', $documentoTipo->id], 'method' => 'patch']) !!}

                        @include('documento_tipos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection