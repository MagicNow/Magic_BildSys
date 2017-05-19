@extends('layouts.front')
@section('styles')
    <style type="text/css">
        .tooltip-inner {
            max-width: 500px;
            text-align: left !important;
        }
        .content {
            min-height: 100px !important;
        }
    </style>
@stop

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-6">
                    <h3 class="pull-left title">
                        <a href="{{ url('/compras') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Comprar Insumos
                    </h3>
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
        <div class="row">
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
                                        {!! Form::select('subgrupo2_id',[''=>'-'], null, [
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
                                        {!! Form::select('subgrupo3_id',[''=>'-'], null, [
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
                                        {!! Form::select('servico_id',[''=>'-'], null, [
                                            'class'=>'form-control select2',
                                            'id'=>'servico_id',
                                            'disabled'=>'disabled',
                                            'onchange'=>'selectgrupo(this.value, null, \'servicos\', \'servico\')'
                                            ]) !!}
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="js-datatable-filter-form">
                                        {!! Form::label('planejamento_id', 'Planejamento:') !!}
                                        {!!
                                          Form::select('planejamento_id', $planejamentos, (isset($planejamento) ? $planejamento->id : null), [
                                            'class'=>'form-control select2',
                                            'id'=>'planejamento_id'
                                            ]) !!}
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="js-datatable-filter-form">
                                        {!! Form::label('insumo_grupos_id', 'Grupo de insumo:') !!}
                                        {!!
                                          Form::select('insumo_grupos_id', $insumoGrupos, (isset($insumoGrupo) ? $insumoGrupo->id : null), [
                                            'class'=>'form-control select2',
                                            'id'=>'insumo_grupos_id'
                                            ]) !!}
                                    </div>
                                </div>

                                <input type="hidden" name="obra_id" value="{{$obra->id}}">

                                <div class="col-md-12" id="list-insumos"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-info btn-lg btn-flat pull-right" onclick="getQueryDataTable();">
                    Comprar saldo de todos os insumos
                </button>
            </div>
            @include('adminlte-templates::common.errors')
        </div>
    </div>

    <div class="content">
            @include('ordem_de_compras.obras-insumos-table')
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
    @parent
    <script type="text/javascript">

        function quantidadeCompra(id, obra_id, grupo_id, subgrupo1_id, subgrupo2_id, subgrupo3_id, servico_id, value) {
            $.ajax({
                url: "{{url('/compras/'.(isset($obra) ? $obra->id : $planejamento->id).'/addCarrinho')}}",
                data: {
                    'id' : id,
                    'obra_id': obra_id,
                    'grupo_id' : grupo_id,
                    'subgrupo1_id' : subgrupo1_id,
                    'subgrupo2_id' : subgrupo2_id,
                    'subgrupo3_id' : subgrupo3_id,
                    'servico_id' : servico_id,
                    'quantidade_compra' : (value == '' ? 0 : value),
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST"
            }).done(function(retorno) {
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            });
        }

        function totalCompra(id, obra_id, grupo_id, subgrupo1_id, subgrupo2_id, subgrupo3_id, servico_id, value){
            $.ajax({
                url: "{{url('/compras/'.(isset($obra) ? $obra->id : $planejamento->id).'/totalParcial')}}",
                data: {
                    'id' : id,
                    'obra_id': obra_id,
                    'grupo_id' : grupo_id,
                    'subgrupo1_id' : subgrupo1_id,
                    'subgrupo2_id' : subgrupo2_id,
                    'subgrupo3_id' : subgrupo3_id,
                    'servico_id' : servico_id,
                    'total' : value,
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST"
            }).done(function(retorno) {
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            });
        }

        function comprarTudo(id, obra_id, grupo_id, subgrupo1_id, subgrupo2_id, subgrupo3_id, servico_id, qtd_total){
            $.ajax({
                url: "{{url('/compras/'.(isset($obra) ? $obra->id : $planejamento->id).'/comprarTudo')}}",
                data: {
                    'id' : id,
                    'obra_id': obra_id,
                    'grupo_id' : grupo_id,
                    'subgrupo1_id' : subgrupo1_id,
                    'subgrupo2_id' : subgrupo2_id,
                    'subgrupo3_id' : subgrupo3_id,
                    'servico_id' : servico_id,
                    'qtd_total' : qtd_total,
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST"
            }).done(function(retorno) {
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            });
        }

        function trocar(id){
            console.log(id);
            $.ajax({
                url: "{{url('/compras/trocaInsumos')}}",
                data: {
                    'id' : id
                },
                type: "GET"
            }).done(function(retorno) {
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            });
        }

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
                    $('#subgrupo1_id').val(null).trigger('change');
                    $('#subgrupo2_id').val(null).trigger('change');
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo1_id').attr('disabled',true);
                    $('#subgrupo2_id').attr('disabled',true);
                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'subgrupo2_id'){
                    $('#subgrupo2_id').val(null).trigger('change');
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo2_id').attr('disabled',true);
                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'subgrupo3_id'){
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'servico_id'){
                    $('#servico_id').attr('disabled',true);
                }
            }
        }

        $(function () {
          $(document).on('draw.dt', function() {
            $('.js-blur-on-enter').on('keypress', function(event) {
              if(event.which === 13) {
                event.currentTarget.blur();
              }
            });
          });

            $('[data-toggle="tooltip"]').tooltip();

            $('.js-datatable-filter-form :input').on('change', function (e) {
                window.LaravelDataTables["dataTableBuilder"].draw();
            });

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

        function formatResult (obj) {
            if(obj.nome) {
                if (obj.loading) return obj.text;

                var markup = "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
                "   </div>" +
                "</div>";
            }else{
                if (obj.loading) return obj.text;

                var markup = "<div class='select2-result-obj clearfix'>" +
                        "   <div class='select2-result-obj__meta'>" +
                        "       <div class='select2-result-obj__title'>" + obj.tarefa + "</div>" +
                        "   </div>" +
                        "</div>";
            }

            return markup;
        }

        function formatResultSelection (obj) {
            if(obj.nome){
                return obj.nome;
            }
            if(obj.tarefa){
                return obj.tarefa;
            }
            return obj.text;
        }

        function getQueryDataTable() {
            var queries = window.LaravelDataTables["dataTableBuilder"].ajax.json().queries;
            var obj = queries[queries.length - 1];
            var bindings = obj.bindings;
            var query = obj.query;

            $.ajax({
                method: "POST",
                url: "/ordens-de-compra/carrinho/comprar-tudo-de-tudo",
                data: {
                    'bindings': bindings,
                    'query': query,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function () {
                window.location.href = '/ordens-de-compra/carrinho';
            });
        }
    </script>
@endsection
