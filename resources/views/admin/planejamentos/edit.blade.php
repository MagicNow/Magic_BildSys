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
                   {!! Form::model($planejamento, ['route' => ['admin.planejamentos.update', $planejamento->id], 'method' => 'patch']) !!}

                        @include('admin.planejamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection