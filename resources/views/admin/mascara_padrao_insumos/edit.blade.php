@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1><button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            Máscara padrão / insumos
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($mascaraPadraoInsumo, ['route' => ['admin.mascara_padrao_insumos.update', $mascaraPadraoInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.mascara_padrao_insumos.edit_fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection