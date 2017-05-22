@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1> Contratos </h1>
  </section>
  <div class="content">
    <div class="box box-primary">
      <div class="box-body">
        @include('partials.filter-grupos-de-orcamento')
        <div class="row">
          <div class="col-sm-3">
            <h4>Data</h4>
          </div>
          <div class="col-sm-3">
            <h4>Fornecedor</h4>
            {!! Form::select('fornecedores[]', $fornecedores, null ,['class' => 'form-control select2']) !!}
          </div>
          <div class="col-sm-3">
            <h4>Obra</h4>
            {!! Form::select('obras[]', $obras, null, ['class' => 'form-control select2']) !!}
          </div>
          <div class="col-sm-3">
            <h4>Status</h4>
            {!! Form::select('status[]', $status, null, ['class' => 'form-control select2']) !!}
          </div>
        </div>
      </div>
      <div class="box-body">
        @include('contratos.table')
      </div>
    </div>
  </div>
@endsection

