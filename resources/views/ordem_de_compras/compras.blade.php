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
                        <label for="select_obra">Obra</label>
                        {!! Form::select('obra_id', [''=>'Obra...']+$obras, null, [
                                                                    'id'=>'select_obra',
                                                                    'class'=>'form-control select2 input-lg',
                                                                    'onchange'=>'escolheObra(this.value);'
                                                                    ]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="planejamento_id">Atividade/Tarefa</label>
                                {!! Form::select('planejamento_id', [''=>'Atividade/Tarefa...'], null, [
                                                'class'=>'form-control',
                                                'disabled'=>'disabled',
                                                'onchange'=>'atualizaCalendarioPorTarefa(this.value);',
                                                'id'=>'planejamento_id'
                                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="planejamento_id">Grupo de Insumo</label>
                                {!! Form::select('insumo_grupo_id', [''=>'Filtrar Grupo de Insumo...']+
                                            \App\Models\InsumoGrupo::pluck('nome','id')->toArray(), null, [
                                                'class'=>'form-control select2',
                                                'onchange'=>'atualizaCalendarioPorInsumoGrupo(this.value);',
                                                'id'=>'insumo_grupo_id'
                                                ]) !!}
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

        function escolheObra(obra_id){
            planejamento_id = null;

            $("#planejamento_id").val('').change();
            obra = obra_id;
            if(obra_id){
                $("#btn-comprar-calendario").attr("href", "compras/obrasInsumos/?obra_id="+obra_id);
                $('#btn-comprar-calendario').css('pointer-events','auto');
                $('#btn-comprar-calendario').addClass('btn-success');
                $('#planejamento_id').attr('disabled',false);
            }else{
                $('#btn-comprar-calendario').removeClass('btn-success');
                $('#btn-comprar-calendario').css('pointer-events','none');
                $('#planejamento_id').attr('disabled',true);

            }
            atualizaCalendario();
        }


        function atualizaCalendario() {

//            planejamento_id = null;

//            $("#planejamento_id").val('').change();

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
            calendar.setOptions({events_source: '{{ url('planejamentos/lembretes') }}' + queryString});
            calendar.view();
        }

        function atualizaCalendarioPorTarefa(tarefa) {
            planejamento_id = tarefa;
            atualizaCalendario();
        }

        function atualizaCalendarioPorInsumoGrupo(insumo_grupo) {
            insumo_grupo_id = insumo_grupo;
            atualizaCalendario();
        }
        $(function () {
            $('#planejamento_id').select2({
                allowClear: true,
                placeholder: "-",
                language: "pt-BR",
                ajax: {
                    url: "/planejamentosByObra",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            obra_id:obra
                        };
                    },

                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1

            });

            calendar = $('#calendar').calendar({
                language: 'pt-BR',
                view: 'month',
                tmpl_path: 'tmpls/',
                tmpl_cache: false,
                day: '{{ date('Y-m-d') }}',
                onAfterEventsLoad: function (events) {
                    if (!events) {
                        return;
                    }
                    var list = $('#eventlist');
                    list.html('');

//                    $.each(events, function (key, val) {
//                        $(document.createElement('tr'))
//                                .html(  '<td>'+val.inicio+'</td>'+
//                                        '<td>'+val.obra+'</td>'+
//                                        '<td>'+val.tarefa+'</td>'+
//                                        '<td>'+val.grupo+'</td>'+
//                                        '<td>'+(val.url != '#' ? '<a href="' + val.url + '" class="btn btn-sm btn-flat btn-primary"> ' +
//                                        ' <i class="fa fa-shopping-cart" aria-hidden="true"></i> Comprar</a>' : '')+'</td>'
//                                )
//                                .appendTo(list);
//                    });
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
//            tmpl_path: "/tmpls/",
                events_source: '{{ url('planejamentos/lembretes') }}'
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