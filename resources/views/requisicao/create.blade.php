@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Requisições
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'requisicao.store']) !!}

                        @include('requisicao.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('#insumos-table,#responsive-example-tablee').stacktable();

            $(document).on('change','#local', function(e) {

                e.preventDefault();

                var obra = $('#obra_id');
                var local = $('#local');
                var torre = $('#torre');

                torre.empty();

                if (obra.val() != '' && local.val() == 'torre') {

                    $.ajax({

                        url: '/obras/torre/'+obra.val(),
                        dataType: 'JSON',
                        cache: false,
                        type: "GET"

                    }).done(function (response) {

                        if (response.success) {

                            torre.append('<option value="">Selecione uma Torre</option>');

                            $.each(response.torres , function(i, val) {

                                torre.append('<option value="'+response.torres[i]['nome']+'">'+response.torres[i]['nome']+'</option>');
                            });
                        }
                    })

                }
            })


            $(document).on('change','#torre', function(e) {

                e.preventDefault();

                var obra = $('#obra_id');
                var local = $('#local');
                var torre = $('#torre');
                var pavimento = $('#pavimento');

                pavimento.empty();

                if (obra.val() != '' && local.val() == 'torre' && torre.val() != '') {

                    $.ajax({

                        url: '/requisicao/get-pavimentos-obra/'+obra.val()+'/torre/'+torre.val(),
                        dataType: 'JSON',
                        cache: false,
                        type: "GET"

                    }).done(function (response) {

                        if (response.success) {

                            pavimento.append('<option value="">Selecione um Pavimento</option>');

                            $.each(response.pavimentos , function(i, val) {

                                pavimento.append('<option value="'+response.pavimentos[i]['pavimento']+'">'+response.pavimentos[i]['pavimento']+'</option>');
                            });
                        }
                    })

                }
            })


            $(document).on('change','#pavimento', function(e) {

                e.preventDefault();

                var obra = $('#obra_id');
                var local = $('#local');
                var torre = $('#torre');
                var pavimento = $('#pavimento');
                var trecho = $('#trecho');

                trecho.empty();

                if (obra.val() != '' && local.val() == 'torre' && torre.val() != '' && pavimento.val() != '') {

                    $.ajax({

                        url: '/requisicao/get-trechos-obra/'+obra.val()+'/torre/'+torre.val()+'/pavimento/'+pavimento.val(),
                        dataType: 'JSON',
                        cache: false,
                        type: "GET"

                    }).done(function (response) {

                        if (response.success) {

                            trecho.append('<option value="">Selecione um Trecho</option>');

                            $.each(response.trechos , function(i, val) {

                                trecho.append('<option value="'+response.trechos[i]['trecho']+'">'+response.trechos[i]['trecho']+'</option>');
                            });
                        }
                    })

                }
            })

            $(document).on('change','#pavimento', function(e) {

                e.preventDefault();

                var obra = $('#obra_id');
                var local = $('#local');
                var torre = $('#torre');
                var pavimento = $('#pavimento');
                var andar = $('#andar');

                andar.empty();

                if (obra.val() != '' && local.val() == 'torre' && torre.val() != '' && pavimento.val() != '') {

                    $.ajax({

                        url: '/requisicao/get-andares-obra/'+obra.val()+'/torre/'+torre.val()+'/pavimento/'+pavimento.val(),
                        dataType: 'JSON',
                        cache: false,
                        type: "GET"

                    }).done(function (response) {

                        if (response.success) {

                            andar.append('<option value="">Selecione um Andar</option>');

                            $.each(response.andares , function(i, val) {

                                andar.append('<option value="'+response.andares[i]['andar']+'">'+response.andares[i]['andar']+'</option>');
                            });
                        }
                    })

                }
            })


            $(document).on('change','#trecho', function(e) {

                var btnInsumos = $('#js-btn-buscar-insumos');
                var insumosTable = $('#insumos-table');

                if ($(this).val() != '') {

                    enableDisable('#andar', true);
                    btnInsumos.removeClass('hide');

                } else {

                    enableDisable('#andar', false);
                    btnInsumos.addClass('hide');
                    insumosTable.addClass('hide');
                }

            })

            $(document).on('change','#andar', function(e) {

                var btnInsumos = $('#js-btn-buscar-insumos');
                var insumosTable = $('#insumos-table');

                if ($(this).val() != '') {

                    enableDisable('#trecho', true);
                    btnInsumos.removeClass('hide');

                } else {

                    enableDisable('#trecho', false);
                    btnInsumos.addClass('hide');
                    insumosTable.addClass('hide');
                }

            })


            $(document).on('click','#js-btn-buscar-insumos', function(e) {

                e.preventDefault();

               var insumosTable = $('#insumos-table');

               insumosTable.removeClass('hide');

                var obra = $('#obra_id');
                var torre = $('#torre');
                var pavimento = $('#pavimento');
                var andar = $('#andar');
                var trecho = $('#trecho');

                //var teste = encodeURI(torre.val());

                $.ajax({

                    url: '/requisicao/get-insumos-obra/',
                    dataType: 'JSON',
                    cache: false,
                    type: 'GET',
                    processData: false,
                    data: 'obra='+obra.val()+'&torre='+torre.val()+'&pavimento='+pavimento.val()+'&andar=' + andar.val() + '&trecho=' + trecho.val()
                }).done(function (response) {


                })

            })

            function enableDisable(fieldP,status) {

                var field = $(fieldP);

                field.select2({
                    disabled: status,
                    theme: "bootstrap"
                });
            }

        })
    </script>
@endsection
