@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Solicitação de insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($solicitacaoInsumo, ['route' => ['admin.solicitacaoInsumos.update', $solicitacaoInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.solicitacao_insumos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection