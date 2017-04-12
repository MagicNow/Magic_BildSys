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
                        {!! Form::select('obra_id', [''=>'Obra...']+$obras, null, ['class'=>'form-control', 'onchange'=>'atualizaCalendario(this.value);']) !!}
                    </div>
                    <div class="col-md-3">
                        <a href="{{ url('comprar') }}" class="btn btn-success btn-block"><i
                                    class="fa fa-shopping-cart"></i> Comprar</a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ url('ordens-de-compras') }}" class="btn btn-primary btn-block"><i
                                    class="fa fa-shopping-basket"></i> Ordens de Compra</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
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
                        <h3>Lembretes</h3>
                        <div id="eventlist">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var calendar = null;
        function atualizaCalendario(obra_id) {
            calendar.setOptions({events_source: '{{ url('planejamentos/lembretes') }}' + (obra_id > 0 ? '?obra_id=' + obra_id : '')});
            calendar.view();
        }
        $(function () {
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

                    $.each(events, function (key, val) {
                        $(document.createElement('li'))
                                .html((val.url != '#' ? '<a href="' + val.url + '">' : '') + val.inicio + ': ' + val.title + (val.url != '#' ? '</a>' : ''))
                                .appendTo(list);
                    });
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