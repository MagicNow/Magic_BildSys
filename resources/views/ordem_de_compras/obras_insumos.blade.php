@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-6">
                    <span class="pull-left title">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Comprar Insumos
                    </span>
                </div>
                <div class="col-md-6 text-right">

                        <button type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                            Incluir Insumo
                        </button>

                        <button type="button" class="btn btn-success btn-lg btn-flat" data-dismiss="modal">
                            Comprar Tudo
                        </button>

                        <button type="button" class="btn btn-success btn-lg btn-flat" data-dismiss="modal">
                            Fechar Ordem
                        </button>

                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box-body" id='app'>
            {{--<tabela--}}
                    {{--api-url="/insumos_json" //required--}}
                    {{--api-filtros="/obras_insumos_filter" //opcional--}}
                    {{--Parametros necessários na consulta inicial sem filtros no estilo dict--}}
                    {{--v-bind:params="{planejamento_id : 1}"--}}
                    {{--Ações possiveis do table ver acoes em table.vue props.actions--}}
                    {{--v-bind:actions="{filtros: true, troca: true, adicionar: true}"--}}
                    {{--Colunas que devem ser listadas e seus respectivos labels--}}
                    {{--v-bind:colunas="[--}}
                        {{--{campo_db: 'nome', label: 'insumos'},--}}
                        {{--{campo_db: 'qtd_total', label: 'quantidade'},--}}
                        {{--{campo_db: 'preco_total', label: 'saldo'},--}}
                    {{--]"--}}
            {{-->--}}
            {{--</tabela>--}}
            <tabela
                    api-url="/insumos_json"
                    api-filtros="/obras_insumos_filter"
                    v-bind:params="{@if (isset($planejamento_id)) planejamento_id: {{$planejamento_id}} @endif }"
                    v-bind:actions="{filtros: true, troca: true, adicionar: true}"
                    v-bind:colunas="[
                        {campo_db: 'nome', label: 'insumos'},
                        {campo_db: 'qtd_total', label: 'quantidade'},
                        {campo_db: 'preco_total', label: 'saldo'},
                    ]"
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