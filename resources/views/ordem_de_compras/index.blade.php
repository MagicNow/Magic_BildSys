@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                <span class="pull-left title">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Ordens de compra
                </span>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-success button-large-green" data-dismiss="modal">
                        Comprar insumos
                    </button>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box-body" id="app">

            @include('layouts.filters')
            <tabela
                api-url="/api/listagem-ordens-de-compras"
                v-bind:params="{}"
                v-bind:colunas="[{campo_db: 'id', label: 'identificador'},{campo_db: 'oc_status_id', label: 'status'},{campo_db: 'obra_id', label: 'obra'},{campo_db: 'aprovado', label: 'aprovado'},{campo_db: 'user_id', label: 'usuario'}]">
            </tabela>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
@endsection
