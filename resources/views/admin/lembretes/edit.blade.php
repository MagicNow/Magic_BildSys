@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Lembrete
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($lembrete, ['route' => ['admin.lembretes.update', $lembrete->id], 'method' => 'patch']) !!}

                        @include('admin.lembretes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection