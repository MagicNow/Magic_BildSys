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
                    <div class="col-md-8">
                        {!! Form::select('obra_id', [''=>'Obra...']+$obras, null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('comprar') }}" class="btn btn-success btn-block"><i class="fa fa-shopping-cart"></i> Comprar</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('ordemDeCompras') }}" class="btn btn-primary btn-block"><i class="fa fa-shopping-basket"></i> Ordens de Compra</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
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
                    <div class="col-md-4">
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
    $(function(){
        var calendar = $('#calendar').calendar({
            language: 'pt-BR',
            view: 'month',
            tmpl_path: 'tmpls/',
            tmpl_cache: false,
            day: '{{ date('Y-m-d') }}',
            onAfterEventsLoad: function(events) {
                if(!events) {
                    return;
                }
                var list = $('#eventlist');
                list.html('');

                $.each(events, function(key, val) {
                    $(document.createElement('li'))
                            .html('<a href="' + val.url + '">' + val.title + '</a>')
                            .appendTo(list);
                });
            },
            onAfterViewLoad: function(view) {
                $('.page-header h3').text(this.getTitle());
                $('.btn-group button').removeClass('active');
                $('button[data-calendar-view="' + view + '"]').addClass('active');
            },
            classes: {
                months: {
                    general: 'label'
                }
            },
//            tmpl_path: "/tmpls/",
            events_source: function () {
                return [{
                "id": 293,
                "title": "Event 1",
                "url": "http://example.com",
                "class": "event-important",
                "start": {{ DateTime::createFromFormat('Y-m-d',date('Y-m-d'))->format('u') }}, // Milliseconds
                "end": {{ DateTime::createFromFormat('Y-m-d',date('Y-m-d'))->format('u') }} // Milliseconds
                }]
            }
        });

        $('.btn-group button[data-calendar-nav]').each(function() {
            var $this = $(this);
            $this.click(function() {
                calendar.navigate($this.data('calendar-nav'));
            });
        });

        $('.btn-group button[data-calendar-view]').each(function() {
            var $this = $(this);
            $this.click(function() {
                calendar.view($this.data('calendar-view'));
            });
        });
    });
</script>
@stop