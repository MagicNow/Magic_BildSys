@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Template Planilha
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($templatePlanilha, ['route' => ['admin.templatePlanilhas.update', $templatePlanilha->id], 'method' => 'patch']) !!}

                        @include('admin.template_planilhas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection