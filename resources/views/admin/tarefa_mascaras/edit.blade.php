@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Planejamento orçamento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($planejamentoOrcamento, ['route' => ['admin.planejamentoOrcamentos.update', $planejamentoOrcamento->id], 'method' => 'patch']) !!}

                        @include('admin.planejamento_orcamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection