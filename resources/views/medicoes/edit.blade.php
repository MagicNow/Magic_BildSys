@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Medicao
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($medicao, ['route' => ['medicoes.update', $medicao->id], 'method' => 'patch']) !!}

                        @include('medicoes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection