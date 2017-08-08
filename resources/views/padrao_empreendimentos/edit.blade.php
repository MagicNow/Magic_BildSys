@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Padrão de empreendimento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($padraoEmpreendimento, ['route' => ['padraoEmpreendimentos.update', $padraoEmpreendimento->id], 'method' => 'patch']) !!}

                        @include('padrao_empreendimentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection