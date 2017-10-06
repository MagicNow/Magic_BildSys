$(function () {

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
                type: "GET",

                beforeSend: function() {
                    startLoading();
                }

            }).done(function (response) {

                stopLoading();

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
                type: "GET",

                beforeSend: function() {
                    startLoading();
                }

            }).done(function (response) {

                stopLoading();

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
                type: "GET",

                beforeSend: function() {
                    startLoading();
                }

            }).done(function (response) {

                stopLoading();

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
                type: "GET",

                beforeSend: function() {
                    startLoading();
                }

            }).done(function (response) {

                stopLoading();

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

        $.ajax({

            url: '/requisicao/get-insumos-obra/',
            dataType: 'html',
            cache: false,
            type: 'GET',
            processData: false,
            data: 'obra='+obra.val()+'&torre='+torre.val()+'&pavimento='+pavimento.val()+'&andar=' + andar.val() + '&trecho=' + trecho.val(),

            beforeSend: function() {
                startLoading();
            }

        }).done(function (response) {

            stopLoading();

            $('#body-insumos-table').html(response);
            tabelaMobile('#insumos-table');

        })

    })

    $(document).on('click','.js-btn-modal-comodo', function(e) {

        e.preventDefault();

        var obra = $('#obra_id');
        var torre = $('#torre');
        var pavimento = $('#pavimento');
        var andar = $('#andar');
        var insumo_id = $(this).data('id');

        $.ajax({

            url: '/requisicao/get-insumos-obra-comodo/',
            dataType: 'html',
            cache: false,
            type: 'GET',
            processData: false,
            data: 'obra='+obra.val()+'&torre='+torre.val()+'&pavimento='+pavimento.val()+'&andar=' + andar.val() + '&insumo_id=' + insumo_id

        }).done(function (response) {

            $('#body-insumos-comodo-table').html(response);
            tabelaMobile('insumos-comodo-table');
            $('#modal-insumos-comodo').modal();
            $('#insumo-comodo-modal').val(insumo_id);
            carregaValoresComodos(insumo_id);
        })

    })

    $(document).on('focusout', '.js-input-qtde', function(){

        validaQtdeInsumo($(this));

    })

    $(document).on('focusout', '.js-input-qtde-comodo', function(){

        validaQtdeInsumoComodo($(this));

    })

    $('#modal-insumos-comodo').on('hidden.bs.modal', function (e) {

        var insumo = $('#insumo-comodo-modal').val();
        var total = 0;

        $('input[name="hidden['+insumo+'][]"]').map(function () {

            total = total + parseFloat($(this).val());

        },total)

        $('#'+insumo).val(total);

    })

    function enableDisable(fieldP,status) {

        var field = $(fieldP);

        field.select2({
            disabled: status,
            theme: "bootstrap"
        })
    }

    function validaQtdeInsumo(insumo) {

        var previsto = parseFloat($('#previsto-'+insumo.attr('id')).text());
        var disponivel = parseFloat($('#disponivel-'+insumo.attr('id')).text());
        var estoque = parseFloat($('#estoque-'+insumo.attr('id')).text());
        var qtde = insumo.val();

        if (qtde > disponivel || qtde > estoque || qtde == 0) {

            insumo.val('');

        } else {

            $('#btn-create-requisicao').removeClass('hide');
        }
    }

    function validaQtdeInsumoComodo(insumo) {

        var disponivel = parseFloat($('#disponivel-comodo-'+insumo.data('levantamento')).text());
        var qtde = insumo.val();
        var insumo_pai = $('#'+insumo.data('id'));
        var form = document.forms['form_insumos'];

        if (qtde > disponivel) {

            insumo.val('');

        } else {

            var name_hidden = 'hidden['+insumo.data('id')+'][]';
            var id = $('input[type=hidden][data-levantamento="'+insumo.data('levantamento')+'"]').attr('id');

            if (qtde > 0) {

                $('input[type=hidden][data-levantamento="'+insumo.data('levantamento')+'"]').remove();

                addHidden(
                    form,
                    name_hidden,
                    $('#apartamento-'+insumo.data('levantamento')).text(),
                    $('#comodo-'+insumo.data('levantamento')).text(),
                    insumo.data('levantamento'),
                    qtde,
                    id
                );

            } else {

                $('input[type=hidden][data-levantamento="'+insumo.data('levantamento')+'"]').remove();
            }
        }
    }

    function carregaValoresComodos(insumo) {

        $('input[name="hidden['+insumo+'][]"]').each(function(index) {

            $('#insumo-'+$(this).attr('data-levantamento')).val($(this).val());

        });
    }


    $(document).on('click','#btn-create-requisicao', function (e){

        e.preventDefault();

        var data = {
            obra_id: $('#obra_id').val(),
            local: $('#local').val(),
            torre: $('#torre').val(),
            pavimento: $('#pavimento').val(),
            andar: $('#andar').val(),
            trecho: $('#trecho').val(),
            insumos: criaJsonInsumos(),
            comodos: criaJsonComodos()
        }

        console.log(JSON.stringify(data));

        $.ajax({

            url: '/requisicao',
            dataType: 'JSON',
            cache: false,
            type: 'POST',
            data: data,

            beforeSend: function() {
                startLoading();
            }

        }).done(function (response) {

            stopLoading();

            if (response.success) {

                window.location = '/requisicao';
            }

        })

    })


    $(document).on('click','#btn-update-requisicao', function (e){

        e.preventDefault();

        var data = {
            status_id: $('#status_id').val(),
            insumos: criaJsonInsumos(),
            comodos: criaJsonComodos()
        }

        console.log(JSON.stringify(data));

        $.ajax({

            url: '/requisicao/'+$('#requisicao_id').val(),
            dataType: 'JSON',
            cache: false,
            type: 'PATCH',
            data: data,

            beforeSend: function() {
                startLoading();
            }

        }).done(function (response) {

            stopLoading();

            if (response.success) {

                window.location = '/requisicao';
            }

        })

    })


    function criaJsonComodos() {

        var jsonString = [];

        $('.js-input-qtde').map(function (i) {

            var insumo = $(this);
            var comodo = $('input[name="hidden['+insumo.attr("id")+'][]"]');

            if (comodo.length > 0) {

                for (i = 0; i < comodo.length; i++) {

                    jsonString.push(
                        {
                            "id": $(comodo[i]).attr('id'),
                            "estoque_id": insumo.data('estoque'),
                            "apartamento": $(comodo[i]).data('apartamento'),
                            "comodo": $(comodo[i]).data('comodo'),
                            "qtde": $(comodo[i]).val()
                        }
                    );
                }
            }

        })

        return JSON.stringify(jsonString);
    }

    function criaJsonInsumos() {

        var jsonString = [];

        $('.js-input-qtde').map(function (i) {

            insumo = $(this);

            if ($('input[name="hidden['+insumo.attr("id")+'][]"]').length == 0) {

                jsonString.push(
                    {
                        "id": $(this).data('id'),
                        "estoque_id": $(this).data('estoque'),
                        "qtde": $(this).val()
                    }
                );
            }

        })

        return JSON.stringify(jsonString);

    }










    function addHidden(form, name, apartamento, comodo, id_levantamento, value, idItem) {

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        input.setAttribute("data-apartamento", apartamento);
        input.setAttribute("data-comodo", comodo);
        input.setAttribute("data-levantamento", id_levantamento);
        input.setAttribute("id", idItem);
        form.appendChild(input);
    }

    function tabelaMobile(tabela) {

        $(tabela).each(function(t){
            // Add a unique id if one doesn't exist.
            if (!this.id) {
                this.id = 'table_' + t;
            }
            // Prepare empty variables.
            var headertext = [],
                theads = document.querySelectorAll('#' + this.id + ' thead'),
                headers = document.querySelectorAll('#' + this.id + ' th'),
                tablerows = document.querySelectorAll('#' + this.id + ' th'),
                tablebody = document.querySelector('#' + this.id + ' tbody');
            // For tables with theads...
            for(var i = 0; i < theads.length; i++) {
                // If they have more than 2 columns...
                if (headers.length > 2) {
                    // Add a responsive class.
                    this.classList.add('responsive');
                    // Get the content of the appropriate th.
                    for(var i = 0; i < headers.length; i++) {
                        var current = headers[i];
                        headertext.push(current.textContent.replace(/\r?\n|\r/,''));
                    }
                    // Apply that as a data-th attribute on the corresponding cells.
                    for (var i = 0, row; row = tablebody.rows[i]; i++) {
                        for (var j = 0, col; col = row.cells[j]; j++) {
                            col.setAttribute('data-th', headertext[j]);
                        }
                    }
                }
            }
        });
    }


})