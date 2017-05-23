@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contrato Template
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($contratoTemplate, ['route' => ['admin.contratoTemplates.update', $contratoTemplate->id], 'method' => 'patch']) !!}

                        @include('admin.contrato_templates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection