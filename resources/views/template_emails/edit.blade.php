@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Template Email
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($templateEmail, ['route' => ['templateEmails.update', $templateEmail->id], 'method' => 'patch']) !!}

                        @include('template_emails.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection