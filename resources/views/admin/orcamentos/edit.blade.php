@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Orcamento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($orcamento, ['route' => ['admin.orcamentos.update', $orcamento->id], 'method' => 'patch']) !!}

                        @include('admin.orcamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection