@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Medicao Servico
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($medicaoServico, ['route' => ['medicaoServicos.update', $medicaoServico->id], 'method' => 'patch']) !!}

                        @include('medicao_servicos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection