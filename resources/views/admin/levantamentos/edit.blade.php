@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cronograma de Obra
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($levantamento, ['route' => ['admin.levantatamentos.update', $levantatamento->id], 'method' => 'patch']) !!}

                        @include('admin.levantatamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection