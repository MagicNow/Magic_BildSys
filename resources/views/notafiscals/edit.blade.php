@extends('layouts.front')

@section('content')
    <section class="content-header">
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary" style="clear:both;">
           <div class="box-body">
               <div class="row" id="nota_fiscal">
                   {!! Form::model($notafiscal, ['route' => ['notafiscals.update', $notafiscal->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('notafiscals.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection