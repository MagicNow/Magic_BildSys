@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-8">
                    <span class="pull-left title">
                        <a href="{{ url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                        Trocar insumos
                    </span>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ url()->previous()}}" type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                        Cancelar
                    </a>
                    <a href="{{ url()->previous()}}" type="button" class="btn btn-success btn-lg btn-flat" data-dismiss="modal">
                        Confirmar
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box-body" id='app'>
            Insumo para troca
            <tabela
                    api-url="/compras/trocaInsumosJsonPai/{{$insumo->id}}"
                    api-filtros=""
                    v-bind:params="{}"
                    v-bind:actions="{}"
                    v-bind:colunas="[
                        {campo_db: 'nome', label: 'insumos'},
                        {campo_db: 'qtd_total', label: 'quantidade'},
                        {campo_db: 'preco_total', label: 'saldo'},
                    ]"
            >
            </tabela>
            Troca Por
            <tabela
                    api-url="{{ url('/compras/trocaInsumosJsonFilho?insumo_pai='.$insumo->id.'&planejamento_id='.$planejamento->id) }}"
                    api-filtros=""
                    api-adicionar=""
                    v-bind:params="{}"
                    v-bind:actions="{troca: true}"
                    v-bind:colunas="[
                        {campo_db: 'nome', label: 'insumos'},
                        {campo_db: 'qtd_total', label: 'quantidade'},
                        {campo_db: 'preco_total', label: 'saldo'},
                    ]"
            >
            </tabela>
            <hr>
            <tabela
                    api-url="{{url('/compras/insumosJson?planejamento_id='.$planejamento->id)}}"
                    api-filtros="{{url('/compras/insumosFilters')}}"
                    _token="{{csrf_token()}}"
                    api-adicionar="{{url('/compras/trocaInsumoAction?insumo_pai='.$insumo->id.'&planejamento_id='.$planejamento->id)}}"
                    v-bind:params="{}"
                    v-bind:actions="{filtros: true, adicionar: true}"
                    v-bind:colunas="[
                        {campo_db: 'descricao' , label:'insumo'},
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