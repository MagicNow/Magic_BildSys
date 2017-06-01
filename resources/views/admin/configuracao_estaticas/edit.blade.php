@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Configuração
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($configuracaoEstatica, ['route' => ['admin.configuracaoEstaticas.update', $configuracaoEstatica->id], 'method' => 'patch']) !!}

                        @include('admin.configuracao_estaticas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection