@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Lembrete tipo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($lembreteTipo, ['route' => ['admin.lembreteTipos.update', $lembreteTipo->id], 'method' => 'patch']) !!}

                        @include('admin.lembrete_tipos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection