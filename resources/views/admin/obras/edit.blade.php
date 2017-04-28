@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Obra
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($obra, ['route' => ['admin.obras.update', $obra->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.obras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection