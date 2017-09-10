@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Levantamento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($levantamento, ['route' => ['admin.levantamentos.update', $levantamento->id], 'method' => 'patch']) !!}

                        @include('admin.levantamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection