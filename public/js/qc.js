// Anexo extra
function addEQitemAnexoSave() {
    var formdata = new FormData();
    formdata.append('nome', $('#item_eqt_anexo_nome').val());
    formdata.append('_token', $('meta[name="csrf-token"]').attr('content'));
    var item_eqt_anexo_arquivo = document.getElementById("item_eqt_anexo_arquivo");
    if (!item_eqt_anexo_arquivo.files.length) {
        swal('Escolha um arquivo', '', 'error');
        item_eqt_anexo_arquivo.focus();
        return false;
    }

    formdata.append('arquivo', item_eqt_anexo_arquivo.files[0]);
    startLoading();
    $.ajax({
        type: 'POST',
        url: "/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/adiciona-eqt-anexo",
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        xhr: function () {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                myXhr.upload.addEventListener('progress', function (upl) {
                    /* faz alguma coisa durante o progresso do upload */
                    console.log('uploading', upl);
                }, false);
            }
            return myXhr;
        }
    }).success(function (obj) {
        stopLoading();
        item_eqt =
            '<li class="list-group-item" id="eqt_custom_anexo_' + obj.id + '">' +
            obj.nome +
            '   <div class="btn-group pull-right">' +
            '      <a href="/' + obj.arquivo.replace('public', 'storage') + '" ' +
            '      download="' + obj.nome + '" type="button" class="btn btn-xs btn-flat btn-default"> ' +
            '      <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i> ' +
            '      </a>' +
            '      <button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
            '          <i class="fa fa-pencil" title="Editar" onclick="editarEQTAnexo(' + obj.id + ')" ' +
            '          aria-hidden="true"></i> ' +
            '      </button>' +
            '      <button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
            '          <i class="fa fa-trash" title="Remover" onclick="removerEQTAnexo(' + obj.id + ')" ' +
            '          aria-hidden="true"></i> ' +
            '      </button>' +
            '   </div>' +
            '</li>';

        $('#equalizacaoTecnicaItens').append(item_eqt);
        $('#modalCadastroEQTAnexo').modal('hide');
        // Limpa os dados
        $('#item_eqt_anexo_nome').val('')
        document.getElementById("item_eqt_anexo_arquivo").value = "";
        $('.overlay').remove();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        stopLoading();
        // Optionally alert the user of an error here...
        var textResponse = jqXHR.responseText;
        var alertText = "Confira as mensagens abaixo:\n\n";
        var jsonResponse = jQuery.parseJSON(textResponse);

        $.each(jsonResponse, function (n, elem) {
            alertText = alertText + elem + "\n";
        });
        swal({title: "", text: alertText, type: 'error'});
    });

}

function editEQitemAnexoSave() {
    var formdata = new FormData();
    formdata.append('nome', $('#item_eqt_anexo_nome').val());
    formdata.append('_token', $('meta[name="csrf-token"]').attr('content'));
    var item_eqt_anexo_arquivo = document.getElementById("item_eqt_anexo_arquivo");

    if (item_eqt_anexo_arquivo.files.length) {
        formdata.append('arquivo', item_eqt_anexo_arquivo.files[0]);
    }

    startLoading();

    var item_eqt_id = $('#item_eqt_anexo_id').val();

    $.ajax({
        type: 'POST',
        url: "/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/editar-eqt-anexo/" + item_eqt_id,
        data: formdata,
        cache: false,
        contentType: false,
        processData: false
    }).success(function (obj) {
        item_eqt = obj.nome +
            '   <div class="btn-group pull-right">' +
            '      <a href="/' + obj.arquivo.replace('public', 'storage') + '" ' +
            '      download="' + obj.nome + '" type="button" class="btn btn-xs btn-flat btn-default"> ' +
            '      <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i> ' +
            '      </a>' +
            '      <button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
            '          <i class="fa fa-pencil" title="Editar" onclick="editarEQTAnexo(' + obj.id + ')" ' +
            '          aria-hidden="true"></i> ' +
            '      </button>' +
            '      <button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
            '          <i class="fa fa-trash" title="Remover" onclick="removerEQTAnexo(' + obj.id + ')" ' +
            '          aria-hidden="true"></i> ' +
            '      </button>' +
            '   </div>';
        $('#eqt_custom_anexo_' + obj.id).html(item_eqt);
        $('#modalCadastroEQTAnexo').modal('hide');
        // Limpa os dados
        $('#item_eqt_anexo_nome').val('')
        document.getElementById("item_eqt_anexo_arquivo").value = "";
        stopLoading();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        stopLoading();
        // Optionally alert the user of an error here...
        var textResponse = jqXHR.responseText;
        var alertText = "Confira as mensagens abaixo:\n\n";
        var jsonResponse = jQuery.parseJSON(textResponse);

        $.each(jsonResponse, function (n, elem) {
            alertText = alertText + elem + "\n";
        });
        swal({title: "", text: alertText, type: 'error'});
    });

}

function removerEQTAnexo(qual) {
    swal({
        title: 'Deseja remover este anexo de Equalização Técnica?',
        text: 'Após a remoção não será possível mais recuperar o registro.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, tenho certeza!",
        cancelButtonText: "Não",
        closeOnConfirm: false
    }, function () {
        $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/remover-eqt-anexo/" + qual)
            .success(function (retorno) {
                $('#eqt_custom_anexo_' + qual).remove();
                swal('Removido', '', 'success');
            }).fail(function (jqXHR, textStatus, errorThrown) {
            swal('Erro', jqXHR.responseText, 'error');
        });
    });
}

function editarEQTAnexo(qual) {
    $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/exibir-eqt-anexo/" + qual)
        .success(function (retorno) {
            $('#item_eqt_anexo_nome').val(retorno.nome);

            $('#item_eqt_anexo_arquivo_span').html('<a href="/' + retorno.arquivo.replace('public', 'storage') + '" ' +
                '      download="' + retorno.nome + '" type="button" class="btn btn-xs btn-flat btn-default btn-block"> ' +
                '      Arquivo Atual <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i> ' +
                '      </a>');
            $('#item_eqt_anexo_arquivo_span').show();
            $('#item_eqt_anexo_id').val(retorno.id);

            $('#btn_add_eq_anexo').hide();
            $('#btn_edit_eq_anexo').show();
            $('#modalCadastroEQTAnexoLabel').html('Editar Anexo de Equalização técnica para este Q.C.');
            $('#modalCadastroEQTAnexo').modal('show');

        }).fail(function (jqXHR, textStatus, errorThrown) {
        swal('Erro', jqXHR.responseText, 'error');
    });
}

function addEQitemAnexo() {
    $('#btn_add_eq_anexo').show();
    $('#btn_edit_eq_anexo').hide();
    $('#item_eqt_anexo_arquivo_span').hide();
    $('#modalCadastroEQTAnexoLabel').html('Cadastrar Anexo de Equalização técnica para este Q.C.');
    $('#modalCadastroEQTAnexo').modal('show');
}


// Item Extra

function addEQitemSave() {
    var item_eqt_nome = $('#item_eqt_nome').val();
    var item_eqt_obrigatorio = $('#item_eqt_obrigatorio').is(':checked');
    var item_eqt_descricao = $('#item_eqt_descricao').val();

    $.ajax({
        type: 'POST',
        url: "/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/adiciona-eqt",
        data: {
            nome: item_eqt_nome,
            obrigatorio: (item_eqt_obrigatorio ? 1 : 0),
            descricao: item_eqt_descricao,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).success(function (obj) {
        item_eqt = '<li class="list-group-item" id="eqt_custom_' + obj.id + '"> ' +
            '<i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC" aria-hidden="true"></i> &nbsp;' +
            (obj.obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i> &nbsp; ' : '' ) +
            obj.nome +
            '<div class="btn-group pull-right">' +
            '<button type="button" class="btn btn-xs btn-flat btn-default"> ' +
            ' <i class="fa fa-info-circle" title="' +
            obj.descricao +
            '" onclick="swal(\'' + obj.nome + '\',\'' + ( parseInt(obj.obrigatorio) == 1 ? 'ITEM OBRIGATÓRIO \\n' : '') +
            obj.descricao + '\',\'info\')" ' +
            ' aria-hidden="true"></i> ' +
            ' </button>' +
            '<button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
            ' <i class="fa fa-pencil" title="Editar" onclick="editarEQT(' + obj.id + ')" ' +
            ' aria-hidden="true"></i> ' +
            ' </button>' +
            '<button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
            '   <i class="fa fa-trash" title="Remover" onclick="removerEQT(' + obj.id + ')" ' +
            '   aria-hidden="true"></i> ' +
            '</button>' +
            '</div>' +
            '</li>';
        $('#equalizacaoTecnicaItens').append(item_eqt);
        $('#modalCadastroEQT').modal('hide');
        // Limpa os dados
        $('#item_eqt_nome').val('')
        $('#item_eqt_obrigatorio').attr('checked', false);
        $('#item_eqt_descricao').val('');
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Optionally alert the user of an error here...
        var textResponse = jqXHR.responseText;
        var alertText = "Confira as mensagens abaixo:\n\n";
        var jsonResponse = jQuery.parseJSON(textResponse);

        $.each(jsonResponse, function (n, elem) {
            alertText = alertText + elem + "\n";
        });
        swal({title: "", text: alertText, type: 'error'});
    });

}

function editEQitemSave() {
    var item_eqt_nome = $('#item_eqt_nome').val();
    var item_eqt_obrigatorio = $('#item_eqt_obrigatorio').is(':checked');
    var item_eqt_descricao = $('#item_eqt_descricao').val();
    var item_eqt_id = $('#item_eqt_id').val();

    $.ajax({
        type: 'POST',
        url: "/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/editar-eqt/" + item_eqt_id,
        data: {
            nome: item_eqt_nome,
            obrigatorio: (item_eqt_obrigatorio ? 1 : 0),
            descricao: item_eqt_descricao,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).success(function (obj) {
        item_eqt =
            '<i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC" aria-hidden="true"></i> &nbsp;' +
            ( parseInt(obj.obrigatorio) ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i> &nbsp; ' : '' ) +
            obj.nome +
            '<div class="btn-group pull-right">' +
            '<button type="button" class="btn btn-xs btn-flat btn-default"> ' +
            ' <i class="fa fa-info-circle" title="' +
            obj.descricao +
            '" onclick="swal(\'' + obj.nome + '\',\'' + ( parseInt(obj.obrigatorio) == 1 ? 'ITEM OBRIGATÓRIO \\n' : '') +
            obj.descricao + '\',\'info\')" ' +
            ' aria-hidden="true"></i> ' +
            ' </button>' +
            '<button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
            ' <i class="fa fa-pencil" title="Editar" onclick="editarEQT(' + obj.id + ')" ' +
            ' aria-hidden="true"></i> ' +
            ' </button>' +
            '<button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
            '   <i class="fa fa-trash" title="Remover" onclick="removerEQT(' + obj.id + ')" ' +
            '   aria-hidden="true"></i> ' +
            '</button>' +
            '</div>';
        $('#eqt_custom_' + obj.id).html(item_eqt);
        $('#modalCadastroEQT').modal('hide');
        // Limpa os dados
        $('#item_eqt_nome').val('')
        $('#item_eqt_obrigatorio').attr('checked', false);
        $('#item_eqt_obrigatorio').iCheck('update');
        $('#item_eqt_descricao').val('');
        $('#item_eqt_id').val('');
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Optionally alert the user of an error here...
        var textResponse = jqXHR.responseText;
        var alertText = "Confira as mensagens abaixo:\n\n";
        var jsonResponse = jQuery.parseJSON(textResponse);

        $.each(jsonResponse, function (n, elem) {
            alertText = alertText + elem + "\n";
        });
        swal({title: "", text: alertText, type: 'error'});
    });

}

function removerEQT(qual) {
    swal({
        title: 'Deseja remover esta Equalização Técnica?',
        text: 'Após a remoção não será possível mais recuperar o registro.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, tenho certeza!",
        cancelButtonText: "Não",
        closeOnConfirm: false
    }, function () {
        $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/remover-eqt/" + qual)
            .success(function (retorno) {
                $('#eqt_custom_' + qual).remove();
                swal('Removido', '', 'success');
            }).fail(function (jqXHR, textStatus, errorThrown) {
            swal('Erro', jqXHR.responseText, 'error');
        });
    });
}

function editarEQT(qual) {
    $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/exibir-eqt/" + qual)
        .success(function (retorno) {
            $('#item_eqt_nome').val(retorno.nome);
            if (parseInt(retorno.obrigatorio)) {
                $('#item_eqt_obrigatorio').attr('checked', true);
                $('#item_eqt_obrigatorio').iCheck('check');
                $('#item_eqt_obrigatorio').iCheck('update');
            } else {
                $('#item_eqt_obrigatorio').attr('checked', false);
                $('#item_eqt_obrigatorio').iCheck('uncheck');
                $('#item_eqt_obrigatorio').iCheck('update');
            }

            $('#item_eqt_descricao').val(retorno.descricao);
            $('#item_eqt_id').val(retorno.id);

            $('#btn_add_eq').hide();
            $('#btn_edit_eq').show();
            $('#modalCadastroEQTLabel').html('Editar Equalização técnica para este Q.C.');
            $('#modalCadastroEQT').modal('show');

        }).fail(function (jqXHR, textStatus, errorThrown) {
        swal('Erro', jqXHR.responseText, 'error');
    });
}

function addEQitem() {
    $('#btn_add_eq').show();
    $('#btn_edit_eq').hide();
    $('#modalCadastroEQTLabel').html('Cadastrar Equalização técnica para este Q.C.');
    $('#modalCadastroEQT').modal('show');
}


// Fornecedor

function cadastraFornecedor() {
    funcaoPosCreate = "preencheFornecedor();";
    $.colorbox({
        href: "/admin/fornecedores/create?modal=1",
        iframe: true,
        width: '90%',
        height: '90%',
    });
}

function preencheFornecedor() {
    qtdFornecedores++;
    var nomeFornecedor = novoObjeto.nome;
    var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id' + qtdFornecedores + '">' +
        '<input type="hidden" name="qcFornecedores[][fornecedor_id]" value="' + novoObjeto.id + '">' +
        nomeFornecedor +
        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
        '<i class="fa fa-trash" aria-hidden="true"></i>' +
        '</button>' +
        '</li>';

    $('#fornecedor').val(null).trigger("change");
    $('#fornecedoresSelecionados').append(qcFornecedorHTML);
}

function formatResult(obj) {
    if (obj.loading) return obj.text;

    var markup = "<div class='select2-result-obj clearfix'>" +
        "   <div class='select2-result-obj__meta'>" +
        "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>" +
        "   </div>" +
        "</div>";

    return markup;
}

function formatResultSelection(obj) {
    if (obj.agn_st_nome) {
        return obj.agn_st_nome;
    }
    return obj.text;
}

function formatResultNomeId(obj) {
    if (obj.loading) return obj.text;

    var markup = "<div class='select2-result-obj clearfix'>" +
        "   <div class='select2-result-obj__meta'>" +
        "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
        "   </div>" +
        "</div>";

    return markup;
}

function formatResultSelectionNomeId(obj) {
    if (obj.nome) {
        return obj.nome;
    }
    return obj.text;
}

$(function () {
    $('#fornecedor').select2({
        allowClear: true,
        placeholder: "-",
        language: "pt-BR",
        ajax: {
            url: "/catalogo-acordos/buscar/busca_fornecedores",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
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
        minimumInputLength: 1,
        templateResult: formatResult, // omitted for brevity, see the source of this page
        templateSelection: formatResultSelection // omitted for brevity, see the source of this page

    });
    $('#fornecedor').on('select2:select', function (e) {
        addFornecedor()
    });

    $('#fornecedor_temp').select2({
        allowClear: true,
        placeholder: "Fornecedores Temporários",
        language: "pt-BR",
        ajax: {
            url: "/admin/fornecedores/busca-temporarios",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
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
        minimumInputLength: 1,
        templateResult: formatResultNomeId, // omitted for brevity, see the source of this page
        templateSelection: formatResultSelectionNomeId // omitted for brevity, see the source of this page

    });
    $('#fornecedor_temp').on('select2:select', function (e) {
        addFornecedorTemp();
    });

    $('.tiposEqT input').on('ifChecked', function (event) {
        var tipo_eqt_id = event.target.value;

        $.getJSON("/tipos-equalizacoes-tecnicas/itens/" + tipo_eqt_id)
            .done(function (retorno) {
                if (retorno) {
                    $.each(retorno, function (index, obj) {
                        item_eqt = '<li class="list-group-item eqt_' + tipo_eqt_id + '">' +
                            (obj.obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i> &nbsp; ' : '' ) +
                            obj.nome +
                            '<button type="button" class="btn btn-xs btn-flat btn-default pull-right"> ' +
                            ' <i class="fa fa-info-circle" title="' +
                            obj.descricao
                            + '" onclick="swal(\'' + obj.nome + '\',\'' + obj.descricao + '\',\'info\')" ' +
                            ' aria-hidden="true"></i> ' +
                            ' </button>' +
                            '</li>';
                        $('#equalizacaoTecnicaItens').append(item_eqt);
                    });
                }

            })
            .fail(function () {
                swal('Erro ao buscar Tipo de Equalização Técnica', '', "error");
            });
        $.getJSON("/tipos-equalizacoes-tecnicas/anexos/" + tipo_eqt_id)
            .done(function (retorno) {
                if (retorno) {
                    $.each(retorno, function (index, obj) {
                        item_eqt = '<li class="list-group-item eqt_' + tipo_eqt_id + '">' +
                            obj.nome +
                            '<a href="/' + obj.arquivo.replace('public', 'storage') + '" ' +
                            ' download="' + obj.nome + '" type="button" class="btn btn-xs btn-flat btn-default pull-right"> ' +
                            ' <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i> ' +
                            '</a>' +
                            '</li>';
                        $('#equalizacaoTecnicaItens').append(item_eqt);
                    });
                }

            })
            .fail(function () {
                swal('Erro ao buscar Tipo de Equalização Técnica', '', "error");
            });
    });
    $('.tiposEqT input').on('ifUnchecked', function (event) {
        var tipo_eqt_id = event.target.value;
        $('.eqt_' + tipo_eqt_id).remove();
    });
});
function addFornecedor() {
    qtdFornecedores++;
    if ($('#fornecedor').val()) {
        var nomeFornecedor = $('#fornecedor').select2('data');

        var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id' + qtdFornecedores + '">' +
            '<input type="hidden" name="qcFornecedoresMega[]" value="' + $('#fornecedor').val() + '">' +
            nomeFornecedor[0].agn_st_nome +
            '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
            ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
            '<i class="fa fa-trash" aria-hidden="true"></i>' +
            '</button>' +
            '</li>';

        $('#fornecedor').val(null).trigger("change");
        $('#fornecedoresSelecionados').append(qcFornecedorHTML);
        $('#fornecedor').select2('open');
    }
}

function addFornecedorTemp() {
    qtdFornecedores++;
    if ($('#fornecedor_temp').val()) {
        var nomeFornecedor = $('#fornecedor_temp').select2('data');

        var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id' + qtdFornecedores + '">' +
            '<input type="hidden" name="qcFornecedores[][fornecedor_id]" value="' + $('#fornecedor_temp').val() + '">' +
            nomeFornecedor[0].nome +
            '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
            ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
            '<i class="fa fa-trash" aria-hidden="true"></i>' +
            '</button>' +
            '</li>';

        $('#fornecedor_temp').val(null).trigger("change");
        $('#fornecedoresSelecionados').append(qcFornecedorHTML);
        //                $('#fornecedor_temp').select2('open');
    }
}

function removerFornecedor(qual, qcFornecedorId) {
    if (qcFornecedorId) {
        // Remover no banco
        swal({
            title: 'Deseja remover este fornecedor?',
            text: 'Após a remoção não será possível mais recuperar o registro.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, tenho certeza!",
            cancelButtonText: "Não",
            closeOnConfirm: false
        }, function () {
            $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/remover-fornecedor/" + qual)
                .success(function (retorno) {
                    $('#qcFornecedor_id' + qual).remove();
                    swal('Removido', '', 'success');
                }).fail(function (jqXHR, textStatus, errorThrown) {
                swal('Erro', jqXHR.responseText, 'error');
            });
        });
    } else {
        // Apenas remove o HTML
        $('#qcFornecedor_id' + qual).remove();
    }
}

// Funções de Agrupar e Desagrupar
function desagrupar(qual) {
    // Remover no banco
    swal({
        title: 'Deseja desagrupar este item?',
        text: 'Ao desagrupar qualquer precificação de fornecedores já gravadas serão perdidas.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, tenho certeza!",
        cancelButtonText: "Não",
        closeOnConfirm: false
    }, function () {
        $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/desagrupar/" + qual)
            .success(function (retorno) {
                window.LaravelDataTables["dataTableBuilder"].draw();
                swal('Item ' + qual + ' desagrupado', '', 'success');
            }).fail(function (jqXHR, textStatus, errorThrown) {
            swal('Erro', jqXHR.responseText, 'error');
        });
    });
}

function agrupar() {
    var itens_a_agrupar = [];
    var insumo_a_agrupar = null;
    var erro = false;
    $('.qc_item_checks').each(function (index) {
        if ($(this).is(':checked')) {
            itens_a_agrupar.push($(this).val());
            if (insumo_a_agrupar) {
                if (insumo_a_agrupar != $(this).attr('insumo')) {
                    swal('Não é possível agrupar itens de insumos diferentes', '', 'error');
                    itens_a_agrupar.pop();
                    erro = true;
                }
            } else {
                insumo_a_agrupar = $(this).attr('insumo');
            }
        }
    });
    if (!erro) {
        // Verifica qtd de itens agrupados se é maior que um
        if (itens_a_agrupar.length <= 1) {
            swal('Escolha um ou mais itens para agrupar','','error');
            return false;
        }
        // Confirma agrupamento de itens
        swal({
            title: 'Deseja agrupar estes itens?',
            text: 'Ao agrupar qualquer precificação de fornecedores já gravadas serão perdidas.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, tenho certeza!",
            cancelButtonText: "Não",
            closeOnConfirm: false
        }, function () {
            // Manda os itens para a função de agrupar

            $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/agrupar", {
                type: 'POST',
                data: {
                    itens: itens_a_agrupar,

                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
                .success(function (retorno) {
                    window.LaravelDataTables["dataTableBuilder"].draw();
                    swal('Itens agrupados', '', 'success');
                }).fail(function (jqXHR, textStatus, errorThrown) {
                var textResponse = jqXHR.responseText;
                var alertText = "Confira as mensagens abaixo:\n\n";
                var jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });
                swal('Erro', alertText, 'error');
            });
        });


    }
}

// Funções do Quadro de Concorrência
function abrirConcorrencia(qc){
    if(qc==0){
        qc = quadroDeConcorrenciaId;
    }
    swal({
        title: 'Deseja iniciar a concorrência?',
        text: 'Ao iniciar a concorrência os fornecedores receberão aviso para acessar a plataforma e efetuar as propostas.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#7ed321",
        confirmButtonText: "Sim, inicie a concorrência!",
        cancelButtonText: "Não",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    }, function () {
        $.ajax("/quadro-de-concorrencia/" + qc + "/acao/inicia-concorrencia").success(function (retorno) {
            var texto = '';
            if(retorno.mensagens.length){
                $.each(retorno.mensagens,function(n,elem){
                    texto = texto + elem + "\n";
                });
            }
            swal({
                title:'Concorrência iniciada!',
                text:texto,
                type: 'success'
            },function () {
                document.location.reload();
            });
        }).fail(function (jqXHR, textStatus, errorThrown) {
            var textResponse = jqXHR.responseText;
            var alertText = "Confira as mensagens abaixo:\n\n";
            var jsonResponse = jQuery.parseJSON(textResponse);

            $.each(jsonResponse, function (n, elem) {
                alertText = alertText + elem + "\n";
            });
            swal('Erro', alertText, 'error');
        });

    });
}

function cancelarQC(qual){
    swal({
        title: 'Deseja cancelar este Quadro de concorrência?',
        text: 'Ao cancelar não será mais possível editar os dados deste Q.C.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, Cancelar!",
        cancelButtonText: "Não",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    }, function () {
        $.ajax("/quadro-de-concorrencia/" + qual + "/acao/cancelar").success(function (retorno) {
            var texto = '';
            if(retorno.mensagens.length){
                $.each(retorno.mensagens,function(n,elem){
                    texto = texto + elem + "\n";
                });
            }
            swal({
                title:'Quadro de Concorrência cancelado!',
                text:texto,
                type: 'success'
            },function () {
                window.LaravelDataTables["dataTableBuilder"].draw();
            });
        }).fail(function (jqXHR, textStatus, errorThrown) {
            var textResponse = jqXHR.responseText;
            var alertText = "Confira as mensagens abaixo:\n\n";
            var jsonResponse = jQuery.parseJSON(textResponse);

            $.each(jsonResponse, function (n, elem) {
                alertText = alertText + elem + "\n";
            });
            swal('Erro', alertText, 'error');
        });

    });
}