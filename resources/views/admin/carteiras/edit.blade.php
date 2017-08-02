@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carteira
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carteira, ['route' => ['admin.carteiras.update', $carteira->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.carteiras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection