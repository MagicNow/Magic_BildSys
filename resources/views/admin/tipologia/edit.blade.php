@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipologia
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipologia, ['route' => ['admin.tipologia.update', $tipologia->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.tipologia.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
