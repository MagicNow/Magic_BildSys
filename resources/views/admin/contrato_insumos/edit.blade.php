@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contrato Insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($contratoInsumo, ['route' => ['admin.contratoInsumos.update', $contratoInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.contrato_insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection