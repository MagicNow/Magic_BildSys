@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cronograma de obra
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($cronogramaFisico, ['route' => ['admin.cronograma_fisicos.update', $cronogramaFisico->id], 'method' => 'patch']) !!}

                        @include('admin.cronograma_fisicos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection