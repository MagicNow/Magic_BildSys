@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Equalização Técnica
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipoEqualizacaoTecnica, ['route' => ['admin.tipoEqualizacaoTecnicas.update', $tipoEqualizacaoTecnica->id], 'method' => 'patch']) !!}

                        @include('admin.tipo_equalizacao_tecnicas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection