@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Topologia
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($topologia, ['route' => ['admin.topologia.update', $topologia->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.topologia.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection