@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Lista de Preço Unitário      
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($lpu, ['route' => ['lpu.update', $lpu->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('lpu.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection