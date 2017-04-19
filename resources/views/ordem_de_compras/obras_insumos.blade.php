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
                    <a href="{{url('compras/'.$planejamento->id.'/insumos/'.$insumoGrupo->id)}}" type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                        Incluir Insumo
                    </a>
                 
                    <a href="{{ url('/ordens-de-compra/carrinho') }}" class="btn btn-success btn-lg btn-flat">
                        Fechar Ordem
                    </a>
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
                    api-url="/compras/{{$planejamento->id}}/obrasInsumosJson/{{$insumoGrupo->id}}"
                    api-filtros="/compras/{{$planejamento->id}}/obrasInsumosFilters"
                    api-adicionar="/compras/{{$planejamento->id}}/addCarrinho"
                    _token="{{csrf_token()}}"
                    v-bind:params="{}"
                    v-bind:actions="{
                    filtros: true,
                    troca: true, troca_url:'{{ url('/compras/'.$planejamento->id.'/trocaInsumos/'.$insumoGrupo->id.'/insumo/') }}',
                    troca_remove:'{{ url('/compras/removerInsumoPlanejamento') }}',
                    quantidade: true,
                    adicionar: true,
                    }"
                    v-bind:colunas="[
                        {campo_db: 'nome', label: 'insumos'},
                        {campo_db: 'qtd_total', label: 'quantidade'},
                        {campo_db: 'saldo', label: 'saldo'},
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