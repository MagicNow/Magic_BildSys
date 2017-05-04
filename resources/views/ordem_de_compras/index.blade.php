@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                    <span class="pull-left title">
                       <h3>
                           <button type="button" class="btn btn-link" onclick="history.go(-1);">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                           </button>
                           <span>Ordens de compra</span>
                       </h3>
                    </span>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-success btn-block btn-lg btn-flat" data-dismiss="modal">
                        Comprar insumos
                    </button>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div class="box-body" id='app'>
            <tabela
                    api-url="/api/listagem-ordens-de-compras"
                    api-filtros="/filter-json-ordem-compra"
                    v-bind:params="{}"
                    v-bind:actions="{filtros: true,date:true, status: true, detalhe: true, detalhe_url:'{{ url('/ordens-de-compra/detalhes/') }}'}"
                    v-bind:colunas="[
                    {campo_db: 'id', label: 'núm. o.c'},
                    {campo_db: 'obra', label: 'obra'},
                    {campo_db: 'usuario', label: 'usuário'},
                    {campo_db: 'situacao', label: 'situação'}
                    ]">
            >
            </tabela>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
@endsection
