@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Calendário de compra
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body for-compra">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group js-datatable-filter-form">
                          <label for="select_obra">Obra</label>
                          {!!
                            Form::select(
                              'obra_id',
                              $obras,
                              null,
                              [
                                'id'       => 'select_obra',
                                'class'    => 'form-control select2 input-lg',
                                'onchange' => 'escolheObra(this.value);'
                              ]
                            )
                          !!}
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group js-datatable-filter-form">
                                  <label for="planejamento_id">Tarefa</label>
                                  {!!
                                    Form::select(
                                      'planejamento_id',
                                      $atividades,
                                      null,
                                      [
                                        'class'    => 'form-control select2',
                                        'disabled'=>'disabled',
                                        'onchange' => 'atualizaCalendarioPorTarefa(this.value);',
                                        'id'       => 'planejamento_id'
                                      ]
                                    )
                                  !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="insumo_grupo_id">Grupo de Insumo</label>
                                <div class="form-group js-datatable-filter-form">
                                  {!!
                                    Form::select(
                                      'insumo_grupo_id',
                                      $grupos,
                                      null,
                                      [
                                        'class'    => 'form-control select2',
                                        'onchange' => 'atualizaCalendarioPorInsumoGrupo(this.value);',
                                        'id'       => 'insumo_grupo_id'
                                      ]
                                    )
                                  !!}
                                </div>
                            </div>
							<div class="col-md-4">
                                <label for="carteira_id">Carteiras</label>
                                <div class="form-group js-datatable-filter-form">
                                  {!!
                                    Form::select(
                                      'carteira_id',
                                      $carteiras,
                                      null,
                                      [
                                        'class'    => 'form-control select2',
                                        'onchange' => 'atualizaCalendarioPorCarteira(this.value);',
                                        'id'       => 'carteira_id'
                                      ]
                                    )
                                  !!}
                                </div>
                            </div>
                        </div>
                        <div class="row checkbox js-datatable-filter-form">
                            <div class="col-md-12">
                                <label for="exibir_por_tarefa">
                                  <input type="checkbox"
                                    value="1"
                                    name="exibir_por_tarefa"
                                    id="exibir_por_tarefa">
                                  Exibir por tarefa
                                </label>&nbsp;&nbsp;
                                <label for="exibir_por_carteira">
                                    <input type="checkbox"
                                           value="1"
                                           name="exibir_por_carteira"
                                           id="exibir_por_carteira">
                                    Exibir por carteira
                                </label>
                            </div>
                        </div>
                        <div class="page-header">
                            <div class="text-left form-inline zoom-1 pull-left">
                                <div class="btn-group">
                                    <button class="btn btn-primary" data-calendar-nav="prev"><< Anterior</button>
                                    <button class="btn btn-default" data-calendar-nav="today">Hoje</button>
                                    <button class="btn btn-primary" data-calendar-nav="next">Próximo >></button>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-warning" data-calendar-view="year">Ano</button>
                                    <button class="btn btn-warning active" data-calendar-view="month">Mês</button>
                                    <button class="btn btn-warning" data-calendar-view="week">Semana</button>
                                    {{--<button class="btn btn-warning" data-calendar-view="day">Dia</button>--}}
                                </div>
                            </div>

                            <h3 class="fs18 calendar-month"></h3>
                        </div>

                        <div id="calendar"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <a id="btn-comprar-calendario" style="pointer-events: none;" href="" class="btn btn-block btn-lg btn-flat"><i
                                            class="fa fa-shopping-cart"></i> Comprar</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/ordens-de-compra') }}" class="btn btn-primary btn-block btn-lg btn-flat"><i
                                            class="fa fa-shopping-basket"></i> Ordens de Compra</a>
                            </div>

                            <div class="col-md-12 compras-lembretes">
                                <h3 class="fs20">Lembretes</h3>
                                @include('ordem_de_compras.lembretes-home-table')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var calendar = null;
        var obra = null;
        var planejamento_id = null;
        var insumo_grupo_id = null;
		var carteira_id = null;
        var exibir_por_tarefa = null;
        var exibir_por_carteira = null;

        function escolheObra(obra_id) {
            planejamento_id = null;

            obra = obra_id;

//            $('#filtro_obra')
//              .val($('#select_obra option:selected').text())
//              .trigger( "change" );

            if(obra_id && obra_id != 'todas'){
                $('#planejamento_id').attr('disabled',false);
                $('#btn-comprar-calendario').attr("href", "compras/obrasInsumos/?obra_id="+obra_id);
                $('#btn-comprar-calendario').css('pointer-events','auto');
                $('#btn-comprar-calendario').addClass('btn-success');
            } else {
                $('#btn-comprar-calendario').removeClass('btn-success');
                $('#btn-comprar-calendario').css('pointer-events','none');
                $('#planejamento_id').attr('disabled',true);
            }

            carregaPlanejamentos(obra_id);
            atualizaCalendario();
        }

        function atualizaCalendarioPorTarefa(tarefa) {
            planejamento_id = tarefa;
//            if(tarefa){
//                $('#filtro_tarefa').val($('#planejamento_id option:selected').text()).trigger( "change" );
//            }else{
//                $('#filtro_tarefa').val('').trigger( "change" );
//            }
            atualizaCalendario();
        }

        function carregaPlanejamentos(obra_id){
            $.ajax('{{ url("planejamentosListaByObra") }}', {
                data: {
                    obra_id: obra_id
                }
            })
                    .done(function (retorno) {
                        var options_tarefas = '<option value="" selected>-</option>';
                        $.each(retorno, function (index, valor) {
                            options_tarefas += '<option value="' + valor.id + '">' + valor.tarefa + '</option>';
                        });
                        $('#planejamento_id').html(options_tarefas);
                        $('#planejamento_id').trigger('change.select2');

                        @if(\Illuminate\Support\Facades\Input::get('planejamento_id'))
                            $('#planejamento_id').val('{{\Illuminate\Support\Facades\Input::get('planejamento_id')}}').trigger('change.select2');
                        @endif

                        atualizaCalendario();
                    })
                    .fail(function (retorno) {
                        erros = '';
                        $.each(retorno.responseJSON, function (index, value) {
                            if (erros.length) {
                                erros += '<br>';
                            }
                            erros += value;
                        });
                        swal("Oops", erros, "error");
                    });
        }

        function atualizaCalendarioPorInsumoGrupo(insumo_grupo) {
            insumo_grupo_id = insumo_grupo;
//            if(insumo_grupo){
//                $('#filtro_grupo').val($('#insumo_grupo_id option:selected').text()).trigger( "change" );
//            }else{
//                $('#filtro_grupo').val('').trigger( "change" );
//            }
            atualizaCalendario();
        }

		function atualizaCalendarioPorCarteira(carteira) {
            carteira_id = carteira;
//            if(carteira){
//                $('#filtro_carteira').val($('#carteira_id option:selected').text()).trigger( "change" );
//            }else{
//                $('#filtro_carteira').val('').trigger( "change" );
//            }
            atualizaCalendario();
        }

        function atualizaCalendario() {
            startLoading();

            @if(\Illuminate\Support\Facades\Input::get('obra_id'))
                if('{{\Illuminate\Support\Facades\Input::get('obra_id')}}' != $('#select_obra').val()) {
                    obra = $('#select_obra').val();
                } else {
                    obra = '{{\Illuminate\Support\Facades\Input::get('obra_id')}}';
                }
            @endif

            @if(\Illuminate\Support\Facades\Input::get('planejamento_id'))
                planejamento_id = '{{\Illuminate\Support\Facades\Input::get('planejamento_id')}}';
            @endif

            @if(\Illuminate\Support\Facades\Input::get('insumo_grupo_id'))
                insumo_grupo_id = '{{\Illuminate\Support\Facades\Input::get('insumo_grupo_id')}}';
            @endif

            @if(\Illuminate\Support\Facades\Input::get('carteira_id'))
                carteira_id = '{{\Illuminate\Support\Facades\Input::get('carteira_id')}}';
            @endif

            var queryString = '';
            if(parseInt(obra) > 0 || obra == 'todas'){
                queryString ='?obra_id=' + obra;
            }

            if(parseInt(planejamento_id) > 0){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='planejamento_id=' + planejamento_id;
            }

            if(parseInt(insumo_grupo_id) > 0){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='insumo_grupo_id=' + insumo_grupo_id;
            }

            if(parseInt(carteira_id) > 0){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='carteira_id=' + carteira_id;
            }

            var $exibirPorTarefa = $('#exibir_por_tarefa');
            exibir_por_tarefa = $exibirPorTarefa.prop('checked');
            if(exibir_por_tarefa > 0){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='exibir_por_tarefa=' + exibir_por_tarefa;
            }

            var $exibirPorCarteira = $('#exibir_por_carteira');
            exibir_por_carteira = $exibirPorCarteira.prop('checked');

            if(exibir_por_carteira > 0){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='exibir_por_carteira=' + exibir_por_carteira;
            }

            if(queryString ) {
                if(!calendar) {
                    renderCalendar();
                }

                calendar.setOptions({events_source: '{{ url('lembretes') }}' + queryString});
                calendar.view();
            }

            history.pushState("", document.title, location.pathname+queryString);
            window.LaravelDataTables["dataTableBuilder"].draw();

            stopLoading();
        }

        $(function () {
            @if(\Illuminate\Support\Facades\Input::get('exibir_por_carteira'))
                $('#exibir_por_carteira').iCheck('check');
            @elseif(\Illuminate\Support\Facades\Input::get('exibir_por_tarefa'))
                $('#exibir_por_tarefa').iCheck('check');
            @endif

            if($('#select_obra').val()) {
                escolheObra($('#select_obra').val());
            } else {
                atualizaCalendario();
            }

            $('.btn-group button[data-calendar-nav]').each(function () {
                var $this = $(this);
                $this.click(function () {
                    calendar.navigate($this.data('calendar-nav'));
                });
            });

            $('.btn-group button[data-calendar-view]').each(function () {
                var $this = $(this);
                $this.click(function () {
                    calendar.view($this.data('calendar-view'));
                });
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

        function renderCalendar() {
            startLoading();

            var calendarOptions = {
                language: 'pt-BR',
                view: 'month',
                tmpl_path: 'tmpls/',
                tmpl_cache: false,
                day: 'now',
                onAfterEventsLoad: function (events) {
                    if (!events) {
                        return;
                    }
                    var list = $('#eventlist');
                    list.html('');
                },
                onAfterViewLoad: function (view) {
                    $('.page-header h3').text(this.getTitle());
                    $('.btn-group button').removeClass('active');
                    $('button[data-calendar-view="' + view + '"]').addClass('active');
                },
                classes: {
                    months: {
                        general: 'label'
                    }
                },
                weekbox: false,
                events_source: '/lembretes'
            };

            var $exibirPorTarefa = $('#exibir_por_tarefa');

            calendar = $('#calendar').calendar(calendarOptions);

            $exibirPorTarefa.on('change ifToggled', function(event) {
                var isChecked = $exibirPorTarefa.prop('checked');

                if(isChecked > 0){
                    $('#exibir_por_carteira').iCheck('uncheck');
                }
//                var date = calendar.options.position.start.toISOString().split('T')[0];

//                calendar = $('#calendar').calendar(Object.assign(calendarOptions, {
//                    events_source: '/lembretes?exibir_por_tarefa=' + (+isChecked),
//                    day: date
//                }));

                LaravelDataTables.dataTableBuilder.ajax.url(
                        location.pathname + '?exibir_por_tarefa=' + (+isChecked)
                );
                LaravelDataTables.dataTableBuilder.draw();
                LaravelDataTables.dataTableBuilder.one('draw.dt', function() {
                    LaravelDataTables.dataTableBuilder.column('grupo:name').visible(!isChecked);
                    LaravelDataTables.dataTableBuilder.column('carteiras.nome:name').visible(!isChecked);
                });

                atualizaCalendario();
            });

            var $exibirPorCarteira = $('#exibir_por_carteira');
            $exibirPorCarteira.on('change ifToggled', function(event) {
                var isChecked = $exibirPorCarteira.prop('checked');

                if(isChecked > 0){
                    $('#exibir_por_tarefa').iCheck('uncheck');
                }
//                var date = calendar.options.position.start.toISOString().split('T')[0];

//                calendar = $('#calendar').calendar(Object.assign(calendarOptions, {
//                    events_source: '/lembretes?exibir_por_carteira=' + (+isChecked),
//                    day: date
//                }));

                LaravelDataTables.dataTableBuilder.ajax.url(
                        location.pathname + '?exibir_por_carteira=' + (+isChecked)
                );
                LaravelDataTables.dataTableBuilder.draw();
                LaravelDataTables.dataTableBuilder.one('draw.dt', function() {
                    LaravelDataTables.dataTableBuilder.column('planejamentos.tarefa:name').visible(!isChecked);
                    LaravelDataTables.dataTableBuilder.column('grupo:name').visible(!isChecked);
                });

                atualizaCalendario();
            });

            stopLoading();
        }
    </script>
@stop
