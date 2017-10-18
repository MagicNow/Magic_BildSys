@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Comprador insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($compradorInsumo, ['route' => ['admin.compradorInsumos.update', $compradorInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.comprador_insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection