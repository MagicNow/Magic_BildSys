@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Fornecedores
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($fornecedores, ['route' => ['admin.fornecedores.update', $fornecedores->id], 'method' => 'patch']) !!}

                        @include('admin.fornecedores.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection