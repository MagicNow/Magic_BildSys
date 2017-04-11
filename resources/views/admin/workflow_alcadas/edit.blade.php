@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Alçada
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($workflowAlcada, ['route' => ['admin.workflowAlcadas.update', $workflowAlcada->id], 'method' => 'patch']) !!}

                        @include('admin.workflow_alcadas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection