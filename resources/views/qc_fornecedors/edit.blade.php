@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Qc Fornecedor
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($qcFornecedor, ['route' => ['qcFornecedors.update', $qcFornecedor->id], 'method' => 'patch']) !!}

                        @include('qc_fornecedors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection