@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Regional
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($regional, ['route' => ['regionals.update', $regional->id], 'method' => 'patch']) !!}

                        @include('regionals.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection