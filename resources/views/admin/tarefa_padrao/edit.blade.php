@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Tarefa Padrão
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tarefaPadrao, ['route' => ['admin.tarefa_padrao.update', $tarefaPadrao->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.tarefa_padrao.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection