@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            QC
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($qc, ['route' => ['qc.update', $qc->id], 'method' => 'patch']) !!}

                        @include('qc.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection