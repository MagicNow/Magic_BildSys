@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-6">
                    <span class="pull-left title">
                        <a href="{{ url('/compras') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Comprar Insumos
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    <div class="col-md-4 text-right">
                        <a href="{{url('compras/'.$planejamento->id.'/insumos')}}" type="button" class="btn btn-success button-large-green" data-dismiss="modal">
                            Incluir Insumo
                        </a>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-success button-large-green" data-dismiss="modal">
                            Comprar Tudo
                        </button>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-success button-large-green" data-dismiss="modal">
                            Fechar Ordem
                        </button>
                    </div>
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
                    api-url="/compras/{{$planejamento->id}}/obrasInsumosJson"
                    api-filtros="/compras/{{$planejamento->id}}/obrasInsumosFilters"
                    v-bind:params="{@if (isset($planejamento->id)) planejamento_id: {{$planejamento->id}} @endif }"
                    v-bind:actions="{filtros: true, troca: true}"
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