@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>
      Dashboard
    </h1>
  </section>
  <div class="content">
    <div class="row">
      @if(auth()->user()->fornecedor)
        <div class="col-sm-3">
          <a href="{{ route('quadroDeConcorrencias.index') }}" class="info-box">
            <span class="info-box-icon bg-aqua">
              <i class="ion-social-usd"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Quadros para preencher</span>
              <span class="info-box-number">{{ $quadros }}</span>
            </div>
          </a>
        </div>
      @endif
    </div>
  </div>
@endsection

