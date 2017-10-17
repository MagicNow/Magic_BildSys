@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-6">
                    <span class="pull-left title">
                        @if(isset($obra))
                            <a href="{{ url('/compras/obrasInsumos?obra_id='.$obra->id) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                        @else
                            <a href="{{ url('/compras/obrasInsumos?planejamento_id='.$planejamento->id) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                        @endif
                        Incluir insumos
                    </span>
                </div>
                <div class="col-md-6 text-right">

                    <button type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                        Inserir insumo no orçamento
                    </button>

                    <a href="{{ url()->previous()}}" type="button" class="btn btn-success btn-lg btn-flat" data-dismiss="modal">
                        Finalizar
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
                    api-url="{{url('/compras/insumosJson?planejamento_id='.$planejamento->id)}}"
                    api-filtros="{{url('/compras/insumosFilters')}}"
                    api-adicionar="{{url('/compras/insumosAdd?planejamento_id='.$planejamento->id)}}"
                    _token="{{csrf_token()}}"
                    v-bind:params="{}"
                    v-bind:actions="{filtros: true, adicionar: true}"
                    v-bind:colunas="[
                        {campo_db: 'insumo_cod', label:'cod'},
                        {campo_db: 'unidade_sigla', label:'und.medida'},
                        {campo_db: 'descricao' , label:'descrição'},
                        {campo_db: 'cod_servico' , label:'cod.serviço'},
                        {campo_db: 'servico' , label:'serviço'},
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
@stop