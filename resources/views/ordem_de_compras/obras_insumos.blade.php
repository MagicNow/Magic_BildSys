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
                    @if (isset($obra))
                        <a id="btn-incluir-insumo"  type="button" class="btn btn-default btn-lg btn-flat" data-toggle="modal" data-target="#modalPlanejamentos">
                            Incluir Insumo
                        </a>
                    @else
                        <a href="{{url("compras/insumos") }}?planejamento_id={{$planejamento->id}}"  type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                            Incluir Insumo
                        </a>
                    @endif

                    <a href="{{ url('/ordens-de-compra/carrinho') }}" class="btn btn-success btn-lg btn-flat">
                        Fechar Ordem
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="content">

        <div class="col-md-12">
            <div class="col-md-12 thumbnail">
                <div class="col-md-12">
                    <div class="caption">
                        <div class="card-description">
                            <!-- Grupos de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('grupo_id', 'Grupos:') !!}
                                {!! Form::select('grupo_id', [''=>'-']+$grupos, null, ['class' => 'form-control', 'id'=>'grupo_id','onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos1 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
                                {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos2 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
                                {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos3 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
                                {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');']) !!}
                            </div>

                            <!-- SubGrupos4 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('servico_id', 'Serviço:') !!}
                                {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\')']) !!}
                            </div>
                            <input type="hidden" name="planejamento_id" value="{{$obra->id}}">

                            <div class="col-md-12" id="list-insumos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    @if(isset($planejamento))api-url="/compras/obrasInsumosJson?planejamento_id={{$planejamento->id}}"
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
                   troca: true, troca_url:'{{ url('/compras/trocaInsumos') }}',
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

    <!-- Modal -->
    <div class="modal fade" id="modalPlanejamentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Escolha um planejamento desta obra</h4>
                </div>
                <div class="modal-body">
                    <select class="js-example-data-array"></select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a id="btnGoInsumos" href="" type="button" class="btn btn-primary">Prosseguir</a>
                </div>
            </div>
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
            var url;
            $('#btn-incluir-insumo').click(function (e) {
                e.preventDefault();
                $.get("{{url('planejamentosByObra')}}", {obra_id: "{{$obra->id}}"})
                    .done(function (data) {
                        dados = JSON.parse(JSON.stringify(data));
                        if(dados.length >0){
                            $('#btnGoInsumos').show()
                            $(".js-example-data-array").select2({
                                data: dados
                            })
                            url = '{{url("compras/insumos") }}?planejamento_id='+$(".js-example-data-array").val()+'&obra_id={{$obra->id}}';
                            $('#btnGoInsumos').attr('href', url);
                            $("body").on('change','.js-example-data-array', function () {
                                url = '{{url("compras/insumos") }}?planejamento_id='+$(".js-example-data-array").val();
                                $('#btnGoInsumos').attr('href', url);
                            })
                        }else{
                            $('#btnGoInsumos').hide();
                        }

                    });
            })
        });

        function selectgrupo(id, change, tipo){
            var rota = "{{url('/admin/planejamentos/atividade/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('/admin/planejamentos/atividade/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $('#'+change).html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#'+change).html(options);
                    $('.overlay').remove();
                    $('#'+change).attr('disabled',false);
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }
        @endif
    </script>
@endsection