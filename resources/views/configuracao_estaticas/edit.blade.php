@extends('layouts.front')

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
                   {!! Form::model($configuracaoEstatica, ['route' => ['configuracaoEstaticas.update', $configuracaoEstatica->id], 'method' => 'patch']) !!}

                        @include('configuracao_estaticas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection