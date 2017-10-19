@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Fornecedores
            <button onclick="sincronizaFornecedor({{ $fornecedores->id }});" class="btn btn-primary pull-right btn-sm">
                <i class="fa fa-refresh"></i>
                Sincronizar com Mega (Re-Importar)
            </button>
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body for-edit">
               <div class="row">
                   {!! Form::model($fornecedores, ['route' => ['admin.fornecedores.update', $fornecedores->id], 'method' => 'patch']) !!}

                        @include( 'flash::message' )
                        @include('admin.fornecedores.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection