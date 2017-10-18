@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo de levantamentos
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipoLevantamento, ['route' => ['admin.tipo_levantamentos.update', $tipoLevantamento->id], 'method' => 'patch', 'files'=>true]) !!}

                        @include('admin.tipo_levantamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection