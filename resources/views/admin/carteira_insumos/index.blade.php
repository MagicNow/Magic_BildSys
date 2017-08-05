@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Carteira / insumos</h1>
        <h1>
            <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px;" href="{!! route('admin.carteiraInsumos.deleteblocoview') !!}">
                Remover insumos em bloco
            </a>
        </h1>
        <h1 class="pull-right">
           <a class="btn btn-warning pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="{!! route('admin.carteiraInsumos.semcarteiraview') !!}">
            Ver insumos sem carteira associada
           </a>
        </h1>
		<h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="{!! route('admin.carteiraInsumos.create') !!}">
            Cadastrar insumos a carteira
           </a>
        </h1>		
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.carteira_insumos.table')
            </div>
        </div>
    </div>
@endsection

