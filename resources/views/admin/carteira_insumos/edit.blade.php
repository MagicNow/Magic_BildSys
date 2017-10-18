@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carteira insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carteiraInsumo, ['route' => ['admin.carteiraInsumos.update', $carteiraInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.carteira_insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection