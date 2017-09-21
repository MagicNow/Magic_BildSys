@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pagamento Condicao
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($pagamentoCondicao, ['route' => ['pagamentoCondicaos.update', $pagamentoCondicao->id], 'method' => 'patch']) !!}

                        @include('pagamento_condicaos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection