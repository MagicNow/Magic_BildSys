@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Mascara Insumos
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($mascaraInsumo, ['route' => ['admin.mascara_insumos.update', $mascaraInsumo->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.mascara_insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection