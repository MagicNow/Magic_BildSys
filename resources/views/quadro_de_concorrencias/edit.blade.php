@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Quadro De Concorrencia
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($quadroDeConcorrencia, ['route' => ['quadroDeConcorrencias.update', $quadroDeConcorrencia->id], 'method' => 'patch']) !!}

                        @include('quadro_de_concorrencias.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection