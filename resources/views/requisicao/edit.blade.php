@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Requisicao
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($requisicao, ['route' => ['requisicao.update', $requisicao->id], 'method' => 'patch']) !!}

                        @include('requisicao.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection