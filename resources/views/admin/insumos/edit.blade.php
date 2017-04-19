@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($insumo, ['route' => ['admin.insumos.update', $insumo->id], 'method' => 'patch']) !!}

                        @include('admin.insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection