@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Compras
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="planejamento_id">Tarefa</label>
                                  {!!
                                    Form::select(
                                      'planejamento_id',
                                      $atividades,
                                      null,
                                      [
                                        'class'    => 'form-control select2',
                                        'onchange' => 'atualizaCalendarioPorTarefa(this.value);',
                                        'id'       => 'planejamento_id'
                                      ]
                                    )
                                  !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="planejamento_id">Grupo de Insumo</label>
                                <div class="form-group">
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
                        </div>
                        <div class="form-group">
                          <div class="checkbox">
                            <label for="exibir_por_tarefa">
                              <input type="checkbox"
                                value="1"
                                name="exibir_por_tarefa"
                                id="exibir_por_tarefa">
                              Exibir por tarefa
                            </label>
                          </div>
                        </div>
                        <div class="page-header">
                            <div class="pull-right form-inline">
                                <div class="btn-group">
                                    <button class="btn btn-primary" data-calendar-nav="prev"><< Anterior</button>
                                    <button class="btn btn-default" data-calendar-nav="today">Hoje</button>
                                    <button class="btn btn-primary" data-calendar-nav="next">Próximo >></button>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-warning" data-calendar-view="year">Ano</button>
                                    <button class="btn btn-warning active" data-calendar-view="month">Mês</button>
                                    <button class="btn btn-warning" data-calendar-view="week">Semana</button>
                                    <button class="btn btn-warning" data-calendar-view="day">Dia</button>
                                </div>
                            </div>

                            <h3></h3>
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

                            <div class="col-md-12">
                                <h3>Lembretes</h3>
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
        var exibir_por_tarefa = null;

        function escolheObra(obra_id) {
            planejamento_id = null;

            obra = obra_id;

            $('#filtro_obra')
              .val($('#select_obra option:selected').text())
              .trigger( "change" );

            if(obra_id){
                $('#btn-comprar-calendario').attr("href", "compras/obrasInsumos/?obra_id="+obra_id);
                $('#btn-comprar-calendario').css('pointer-events','auto');
                $('#btn-comprar-calendario').addClass('btn-success');
            } else {
                $('#btn-comprar-calendario').removeClass('btn-success');
                $('#btn-comprar-calendario').css('pointer-events','none');
            }

            atualizaCalendario();
        }


        function atualizaCalendario() {
            var queryString = '';
            if(parseInt(obra) > 0){
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

            calendar.setOptions({events_source: '{{ url('planejamentos/lembretes') }}' + queryString});

            calendar.view();
        }

        function atualizaCalendarioPorTarefa(tarefa) {
            planejamento_id = tarefa;
            if(tarefa){
                $('#filtro_tarefa').val($('#planejamento_id option:selected').text()).trigger( "change" );
            }else{
                $('#filtro_tarefa').val('').trigger( "change" );
            }
            atualizaCalendario();
        }

        function atualizaCalendarioPorInsumoGrupo(insumo_grupo) {
            insumo_grupo_id = insumo_grupo;
            if(insumo_grupo){
                $('#filtro_grupo').val($('#insumo_grupo_id option:selected').text()).trigger( "change" );
            }else{
                $('#filtro_grupo').val('').trigger( "change" );
            }
            atualizaCalendario();
        }

        $(function () {
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
              events_source: '/planejamentos/lembretes'
            };

            var $exibirPorTarefa = $('#exibir_por_tarefa');

            calendar = $('#calendar').calendar(calendarOptions);

            $exibirPorTarefa.on('change ifToggled', function(event) {
              var isChecked = $exibirPorTarefa.prop('checked');
              var date = calendar.options.position.start.toISOString().split('T')[0];

              calendar = $('#calendar').calendar(Object.assign(calendarOptions, {
                events_source: '/planejamentos/lembretes?exibir_por_tarefa=' + (+isChecked),
                day: date
              }))

              LaravelDataTables.dataTableBuilder.ajax.url(
                location.pathname + '?exibir_por_tarefa=' + (+isChecked)
              );
              LaravelDataTables.dataTableBuilder.draw();
              LaravelDataTables.dataTableBuilder.one('draw.dt', function() {
                LaravelDataTables.dataTableBuilder.column('grupo:name').visible(!isChecked);
              });

            });

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
        });
    </script>
@stop
