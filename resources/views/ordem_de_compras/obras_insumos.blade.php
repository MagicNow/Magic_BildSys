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
                    {{--href="{{url('compras/insumos?planejamento_id='.$planejamento->id.'&insumo_grupos_id='    .$insumoGrupo->id)}}"--}}
                    <a id="btn-incluir-insumo"  type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
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
                    @if(isset($planejamento))api-url="/compras/obrasInsumosJson?planejamento_id={{$planejamento->id}}&obras_insumos_id={{$insumoGrupo->id}}"
                    @else api-url="/compras/obrasInsumosJson?obra_id={{$obra->id}}"
                    @endif
                    api-filtros="/compras/obrasInsumosFilters"
                    @if(isset($planejamento))api-adicionar="/compras/{{$planejamento->obra_id}}/{{$planejamento->id}}/addCarrinho"
                    @else api-adicionar="/compras/{{$obra->id}}/addCarrinho"
                    @endif
                    _token="{{csrf_token()}}"
                    v-bind:params="{}"
                    v-bind:actions="{
                   filtros: true,
                   troca: true, troca_url:'{{ isset($planejamento)? url('/compras/'.$planejamento->id.'/trocaInsumos/'.$insumoGrupo->id.'/insumo/') : ''}}',
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

            <input type="hidden" id="e10_2" style="width:300px"/>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const app = new Vue({
            el: '#app'
        });
        @if(isset($obra))
        $(document).ready(function() {
            var dados;
            function format(item) { return item.name; };
            $('#btn-incluir-insumo').click(function (e) {
                e.preventDefault();
                $.get("{{url('planejamentosByObra')}}", {obra_id: "{{$obra->id}}"})
                    .done(function (data) {
                        console.log(data);
                        dados = data;
                        $("#e10_2").select2({
                            data:{ results: names, text: 'name' },
                            formatSelection: format,
                            formatResult: format
                        });

                    });
            })
        });
        @endif
    </script>
@endsection