@extends('layouts.front')
@section('styles')
    <style type="text/css">
        .tooltip-inner {
            max-width: 500px;
            text-align: left !important;
        }
        .content {
            min-height: 170px !important;
        }
    </style>
@stop

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
                        <a href="{{url("compras/insumos/orcamento/".$obra->id)}}" type="button" class="btn btn-default btn-lg btn-flat">
                            Incluir insumo na OC
                        </a>
                    @else
                        <a href="{{url("compras/insumos") }}?planejamento_id={{$planejamento->id}}"  type="button" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                            Incluir insumo na OC
                        </a>
                    @endif

                    <a href="{{ url('/ordens-de-compra/carrinho') }}" class="btn btn-success btn-lg btn-flat">
                        Fechar OC
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @if (isset($obra))
        <div class="col-md-12">
            <div class="col-md-12 thumbnail">
                <div class="col-md-12">
                    <div class="caption">
                        <div class="card-description">
                            <!-- Grupos de insumo Field -->
                            {{--<div class="form-group col-sm-6" style="width:20%">--}}
                                {{--{!! Form::label('grupo_id', 'Grupo:') !!}--}}
                                {{--{!! Form::select('grupo_id', [''=>'-']+$grupos, null, ['class' => 'form-control', 'id'=>'grupo_id','onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\', \'grupo\');']) !!}--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-6" style="width:20%">
                                <div class="js-datatable-filter-form">
                                    {!! Form::label('grupo_id', 'Grupo:') !!}
                                    {!! Form::select('grupo_id',[''=>'-']+$grupos, null, [
                                        'class'=>'form-control select2',
                                        'id'=>'grupo_id',
                                        'onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\', \'grupo\');'
                                        ]) !!}
                                </div>
                            </div>

                            <!-- SubGrupos1 de insumo Field -->
                            {{--<div class="form-group col-sm-6" style="width:20%">--}}
                                {{--{!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}--}}
                                {{--{!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\', \'subgrupo1\');']) !!}--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-6" style="width:20%">
                                <div class="js-datatable-filter-form">
                                    {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
                                    {!! Form::select('subgrupo1_id',[''=>'-'], null, [
                                        'class'=>'form-control select2',
                                        'id'=>'subgrupo1_id',
                                        'disabled'=>'disabled',
                                        'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\', \'subgrupo1\');'
                                        ]) !!}
                                </div>
                            </div>

                            <!-- SubGrupos2 de insumo Field -->
                            {{--<div class="form-group col-sm-6" style="width:20%">--}}
                                {{--{!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}--}}
                                {{--{!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\', \'subgrupo2\');']) !!}--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-6" style="width:20%">
                                <div class="js-datatable-filter-form">
                                    {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
                                    {!! Form::select('subgrupo2_id',[''=>'-']+$grupos, null, [
                                        'class'=>'form-control select2',
                                        'id'=>'subgrupo2_id',
                                        'disabled'=>'disabled',
                                        'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\', \'subgrupo2\');'
                                        ]) !!}
                                </div>
                            </div>

                            <!-- SubGrupos3 de insumo Field -->
                            {{--<div class="form-group col-sm-6" style="width:20%">--}}
                                {{--{!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}--}}
                                {{--{!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\', \'subgrupo3\');']) !!}--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-6" style="width:20%">
                                <div class="js-datatable-filter-form">
                                    {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
                                    {!! Form::select('subgrupo3_id',[''=>'-']+$grupos, null, [
                                        'class'=>'form-control select2',
                                        'id'=>'subgrupo3_id',
                                        'disabled'=>'disabled',
                                        'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\', \'subgrupo3\');'
                                        ]) !!}
                                </div>
                            </div>

                            <!-- SubGrupos4 de insumo Field -->
                            {{--<div class="form-group col-sm-6" style="width:20%">--}}
                                {{--{!! Form::label('servico_id', 'Serviço:') !!}--}}
                                {{--{!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\', \'servico\')']) !!}--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-6" style="width:20%">
                                <div class="js-datatable-filter-form">
                                    {!! Form::label('servico_id', 'Serviço:') !!}
                                    {!! Form::select('servico_id',[''=>'-']+$grupos, null, [
                                        'class'=>'form-control select2',
                                        'id'=>'servico_id',
                                        'disabled'=>'disabled',
                                        'onchange'=>'selectgrupo(this.value, null, \'servicos\', \'servico\')'
                                        ]) !!}
                                </div>
                            </div>

                            <input type="hidden" name="obra_id" value="{{$obra->id}}">

                            <div class="col-md-12" id="list-insumos"></div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ url('ordens-de-compra/carrinho/comprar-tudo-de-tudo?obra_id='.$obra->id) }}" class="btn btn-info btn-lg btn-flat pull-right" id="comprar_tudo_de_tudo">
                Comprar tudo de tudo
            </a>
        </div>
        @endif
        @include('adminlte-templates::common.errors')
    </div>
    <div class="content">
            @include('ordem_de_compras.obras-insumos-table')
    </div>
        {{--<div class="box-body" id='app'>--}}
            {{--<tabela--}}
                    {{--@if(isset($planejamento))--}}
                        {{--api-url="/compras/obrasInsumosJson?planejamento_id={{$planejamento->id}}"--}}
                        {{--api-adicionar="/compras/{{$planejamento->obra_id}}/{{$planejamento->id}}/addCarrinho"--}}
                    {{--@else--}}
                        {{--api-url="/compras/obrasInsumosJson?obra_id={{$obra->id}}"--}}
                        {{--api-adicionar="/compras/{{$obra->id}}/addCarrinho"--}}
                        {{--api-total-parcial="/compras/{{$obra->id}}/totalParcial"--}}
                        {{--api-comprar-tudo="/compras/{{$obra->id}}/comprarTudo"--}}
                    {{--@endif--}}
                        {{--api-filtros="/compras/obrasInsumosFilters"--}}
                    {{--_token="{{csrf_token()}}"--}}
                    {{--v-bind:params="{}"--}}
                    {{--v-bind:actions="{--}}
                   {{--filtros: true,--}}
                   {{--troca: true, troca_url:'{{ url('/compras/trocaInsumos') }}',--}}
                   {{--troca_remove:'{{ url('/compras/removerInsumoPlanejamento') }}',--}}
                   {{--quantidade: true,--}}
                   {{--adicionar: true,--}}
                   {{--tooltip: true,--}}
                   {{--total_parcial: true,--}}
                   {{--comprar_tudo: true,--}}
                   {{--}"--}}
                    {{--v-bind:colunas="[--}}
                       {{--{campo_db: 'nome', label: 'insumos'},--}}
                       {{--{campo_db: 'unidade_sigla', label: 'Unidade de Medida'},--}}
                       {{--{campo_db: 'qtd_total', label: 'quantidade'},--}}
                       {{--{campo_db: 'saldo', label: 'saldo'},--}}
                   {{--]"--}}
            {{-->--}}
            {{--</tabela>--}}
        {{--</div>--}}

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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            $('.js-datatable-filter-form .select2').on('select2:select', function (evt) {
                window.LaravelDataTables["dataTableBuilder"].draw();
            });

            $('#dataTableBuilder').on('preXhr.dt', function ( e, settings, data ) {
                $('.js-datatable-filter-form :input').each(function () {
                    if($(this).attr('type')=='checkbox'){
                        if(data[$(this).prop('name')]==undefined){
                            data[$(this).prop('name')] = [];
                        }
                        if($(this).is(':checked')){
                            data[$(this).prop('name')].push($(this).val());
                        }

                    }else{
                        data[$(this).prop('name')] = $(this).val();
                    }
                });
            });
        });

        {{--@if(isset($obra))--}}
            {{--$(document).ready(function() {--}}
                {{--verificarFiltroGrupos();--}}
                {{--var dados;--}}
                {{--var url;--}}
                {{--array_grupos = [];--}}

                {{--$('#btn-incluir-insumo').click(function (e) {--}}
                    {{--e.preventDefault();--}}
                    {{--$.get("{{url('planejamentosByObra')}}", {obra_id: "{{$obra->id}}"})--}}
                        {{--.done(function (data) {--}}
                            {{--dados = JSON.parse(JSON.stringify(data));--}}
                            {{--if(dados.length >0){--}}
                                {{--$('#btnGoInsumos').show()--}}
                                {{--$(".js-example-data-array").select2({--}}
                                    {{--data: dados--}}
                                {{--})--}}
                                {{--url = '{{url("compras/insumos") }}?planejamento_id='+$(".js-example-data-array").val()+'&obra_id={{$obra->id}}';--}}
                                {{--$('#btnGoInsumos').attr('href', url);--}}
                                {{--$("body").on('change','.js-example-data-array', function () {--}}
                                    {{--url = '{{url("compras/insumos") }}?planejamento_id='+$(".js-example-data-array").val();--}}
                                    {{--$('#btnGoInsumos').attr('href', url);--}}
                                {{--})--}}
                            {{--}else{--}}
                                {{--$('#btnGoInsumos').hide();--}}
                            {{--}--}}

                        {{--});--}}
                {{--});--}}
            {{--});--}}
        {{--@endif--}}

        function selectgrupo(id, change, tipo){
            console.log('id: ', id, 'change: ', change, 'tipo: ', tipo);
            var rota = "{{url('ordens-de-compra/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('ordens-de-compra/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '';
                    options = '<option value="">Selecione</option>';
                    $('#'+change).html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#'+change).html(options);
                    $('#'+change).attr('disabled',false);
                }).fail(function() {
                });
            }else{
                if(change == 'subgrupo1_id'){
                    options = '';
                    $('#subgrupo1_id').attr('disabled',true);
                    $('#subgrupo2_id').attr('disabled',true);
                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }
            }
        }
    </script>
@endsection