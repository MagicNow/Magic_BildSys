@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Grupos de Insumos </h1>
        {{--<h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.insumoGrupos.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>--}}
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.insumo_grupos.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  <script src="/vendor/datatables/buttons.server-side.js"></script>
  {!! $dataTable->scripts() !!}
  <script src="{{ asset('js/insumo-grupo-activation.js') }}"></script>
@endsection
