@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Máscara padrão estrutura
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($mascaraPadraoEstrutura, ['route' => ['admin.mascaraPadraoEstruturas.update', $mascaraPadraoEstrutura->id], 'method' => 'patch']) !!}

                        @include('admin.mascara_padrao_estruturas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection