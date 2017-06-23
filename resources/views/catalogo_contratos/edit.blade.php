@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Catálogo de Acordo
            <span class="pull-right">
                Situação:
                <span class="label label-default" style="background-color: {{ $catalogoContrato->status->cor }}"> {{ $catalogoContrato->status->nome }} </span>
            </span>
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($catalogoContrato, ['route' => ['catalogo_contratos.update', $catalogoContrato->id], 'method' => 'patch', 'files' => true]) !!}

                    @include('catalogo_contratos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection