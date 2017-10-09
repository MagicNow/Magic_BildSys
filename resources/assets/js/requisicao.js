$(function () {

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

            if (qtde > 0) {

                $('input[type=hidden][data-levantamento="'+insumo.data('levantamento')+'"]').remove();

                addHidden(
                    form,
                    name_hidden,
                    $('#apartamento-'+insumo.data('levantamento')).text(),
                    $('#comodo-'+insumo.data('levantamento')).text(),
                    insumo.data('levantamento'),
                    qtde
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













    function addHidden(form, name, apartamento, comodo, id_levantamento, value) {

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        input.setAttribute("data-apartamento", apartamento);
        input.setAttribute("data-comodo", comodo);
        input.setAttribute("data-levantamento", id_levantamento);
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