function workflowCall(item_id, tipo_item, aprovou, elemento, motivo, justificativa_texto) {

    $.ajax('/workflow/aprova-reprova',
        {
            data: {
                id: item_id,
                tipo: tipo_item,
                resposta: aprovou,
                motivo_id: motivo,
                justificativa: justificativa_texto
            }
        }).done(function (retorno) {
        if (retorno.success) {
            if (aprovou) {
                titulo = 'Aprovado';
                conteudoElemento = "";
            } else {
                titulo = 'Reprovado';
                conteudoElemento = "";
            }
            swal(titulo, 'Sua escolha foi salva com sucesso!', "success");
            $('#' + elemento).html(conteudoElemento);
        }
    })
        .fail(function (retorno) {
            console.log(retorno.responseJSON);
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

function workflowAprovaReprova(item_id, tipo_item, aprovou, elemento) {
    if (!aprovou) {
        swal({
                title: "Reprovar este item?",
                text: "<label for='motivo_id'>Escolha um motivo</label>" +
                "<select name='motivo_id' id='motivo_id' class='form-control input-lg' required='required'>" +
                "<option value=''>Escolha...</option>" +
                "<option value='1'>Pq eu quero</option>" +
                "</select><br><label>Escreva uma justificativa</label>: ",
                html: true,
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Justificativa",
                showLoaderOnConfirm: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Reprovar',
                confirmButtonColor: '#DD6B55'
            },
            function (justificativa_texto) {
                if (justificativa_texto === false) return false;

                if (justificativa_texto === "") {
                    swal.showInputError("Escreva uma justificativa!");
                    return false
                }
                motivo = $('#motivo_id').val();
                if (motivo === "") {
                    swal.showInputError("Escolha um motivo!");
                    return false
                }

                workflowCall(item_id, tipo_item, aprovou, elemento, motivo, justificativa_texto);

            });

    } else {
        swal({
                title: "Aprovar este Item?",
                text: "Ao confirmar não será possível voltar atrás",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aprovar",
                cancelButtonText: 'Cancelar',
                closeOnConfirm: false
            },
            function () {
                workflowCall(item_id, tipo_item, aprovou, elemento, null, null);
            });
    }
}