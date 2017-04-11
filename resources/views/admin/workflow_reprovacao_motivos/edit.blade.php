@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivo de Reprovação
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($workflowReprovacaoMotivo, ['route' => ['admin.workflowReprovacaoMotivos.update', $workflowReprovacaoMotivo->id], 'method' => 'patch']) !!}

                        @include('admin.workflow_reprovacao_motivos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection