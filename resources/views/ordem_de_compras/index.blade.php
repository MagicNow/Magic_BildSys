@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
           <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
           </button>
           Ordens de compra
            <a href="{{ url('compras') }}" type="button" class="btn btn-success btn-lg btn-flat pull-right" data-dismiss="modal">
                <i class="fa fa-shopping-cart"></i>
                Calendário de compra
            </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div>
            @include('ordem_de_compras.table')
        </div>
        {{--<div class="box-body" id='app'>--}}
            {{--<tabela--}}
                    {{--api-url="/api/listagem-ordens-de-compras"--}}
                    {{--api-filtros="/filter-json-ordem-compra"--}}
                    {{--v-bind:params="{}"--}}
                    {{--v-bind:actions="{filtros: true,date:true, status: true, detalhe: true, detalhe_url:'{{ url('/ordens-de-compra/detalhes/') }}'}"--}}
                    {{--v-bind:colunas="[--}}
                    {{--{campo_db: 'id', label: 'núm. o.c'},--}}
                    {{--{campo_db: 'obra', label: 'obra'},--}}
                    {{--{campo_db: 'usuario', label: 'usuário'},--}}
                    {{--{campo_db: 'situacao', label: 'situação'}--}}
                    {{--]">--}}
            {{-->--}}
            {{--</tabela>--}}
        {{--</div>--}}
    </div>
@endsection

@section('scripts')
    <script>
//        const app = new Vue({
//            el: '#app'
//        });
    </script>
@endsection
