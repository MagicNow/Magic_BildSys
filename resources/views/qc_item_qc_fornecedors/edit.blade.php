@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Qc Item Qc Fornecedor
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($qcItemQcFornecedor, ['route' => ['qcItemQcFornecedors.update', $qcItemQcFornecedor->id], 'method' => 'patch']) !!}

                        @include('qc_item_qc_fornecedors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection