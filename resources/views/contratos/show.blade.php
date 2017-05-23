@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>
      Contrato #{{ $contrato->id }}
      <small class="label label-default pull-right margin10">
        <i class="fa fa-circle" aria-hidden="true" style="color:{{ $contrato->status->cor }}"></i>
        {{ $contrato->status->nome }}
      </small>
    </h1>
  </section>
@endsection
