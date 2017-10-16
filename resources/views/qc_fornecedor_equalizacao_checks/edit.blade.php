@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Qc Fornecedor Equalizacao Check
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($qcFornecedorEqualizacaoCheck, ['route' => ['qcFornecedorEqualizacaoChecks.update', $qcFornecedorEqualizacaoCheck->id], 'method' => 'patch']) !!}

                        @include('qc_fornecedor_equalizacao_checks.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection