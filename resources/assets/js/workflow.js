var obsAprovador = document.getElementById('obs-aprovador');

if(obsAprovador) {
    //var contrato_id = document.getElementById('contrato_id');
    //var user_id = document.getElementById('user_id');

    //var key = 'contrato_obs_' + user_id.value + '_' + contrato_id.value;
    var key = obsAprovador.dataset.key;

    obsAprovador.value = localStorage.getItem(key);

    var saveObs = _.debounce(function(event) {
        localStorage.setItem(key, obsAprovador.value);
    }, 700);

    obsAprovador.addEventListener('input', saveObs);
    obsAprovador.addEventListener('change', saveObs);
}

function workflowCall(item_id, tipo_item, aprovou, elemento, motivo, justificativa_texto, pai_id, pai_obj, filhos_metodo, shouldReload) {

  var url_aprova_reprova = '/workflow/aprova-reprova';

  if (pai_id > 0) {
    url_aprova_reprova = '/workflow/aprova-reprova-tudo'
  }

  var data = {
    id: item_id,
    tipo: tipo_item,
    resposta: aprovou,
    motivo_id: motivo,
    justificativa: justificativa_texto,
    pai: pai_id,
    pai_tipo: pai_obj,
    filhos_relacionamento: filhos_metodo
  };

  var obsAprovador = document.getElementById('obs-aprovador');

  if (obsAprovador && aprovou) {
    //var contrato_id = document.getElementById('contrato_id');
    //var user_id = document.getElementById('user_id');

    var key = obsAprovador.dataset.key;

    localStorage.removeItem(key);

    data.justificativa = obsAprovador.value;
  }

  return $.ajax(url_aprova_reprova, {
      data: data
    }).done(function(retorno) {
      if (retorno.success) {
        if (aprovou) {
          titulo = 'Aprovado';
          conteudoElemento = '<span class="btn-lg btn-flat text-success" title="Aprovado por você">' +
            '<i class="fa fa-check" aria-hidden="true"></i>' +
            '</span>';
        } else {
          titulo = 'Reprovado';
          conteudoElemento = '<span class="text-danger btn-lg btn-flat" title="Reprovado por você">' +
            '<i class="fa fa-times" aria-hidden="true"></i>' +
            '</span>';
        }
        swal({
            title: titulo,
            text: 'Sua escolha foi salva com sucesso!',
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#7ED32C",
            confirmButtonText: "Ok",
            closeOnConfirm: true
          },
          function() {
            $('#' + elemento).html(conteudoElemento);
            if (pai_id > 0 || shouldReload) {
              window.location.reload();
            }
            swal.close();
          });

      } else {
        swal("Oops", retorno.resposta, "error");
        swal({
            title: 'Oops',
            text: retorno.resposta,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#7ED32C",
            confirmButtonText: "Ok",
            closeOnConfirm: true
          },
          function() {
            if (retorno.refresh) {
              window.location.reload();
            }
            swal.close();
          });
      }
    })
    .fail(function(retorno) {

      erros = '';
      $.each(retorno.responseJSON, function(index, value) {
        if (erros.length) {
          erros += '<br>';
        }
        erros += value;
      });
      swal("Oops", erros, "error");
    });
}

var options_motivos = '';

function workflowAprovaReprova(item_id, tipo_item, aprovou, elemento, nome, pai_id, pai_obj, filhos_metodo, shouldReload) {
  if (!aprovou) {
    swal({
        title: "Reprovar " + nome + "?",
        text: "<label for='motivo_id'>Escolha um motivo</label>" +
          "<select name='motivo_id' id='motivo_id' class='form-control input-lg' required='required'>" +
          options_motivos +
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
      function(justificativa_texto) {
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

        workflowCall(item_id, tipo_item, aprovou, elemento, motivo, justificativa_texto, pai_id, pai_obj, filhos_metodo, shouldReload)
      });

  } else {
    swal({
        title: "Aprovar " + nome + "?",
        text: "Ao confirmar não será possível voltar atrás",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aprovar",
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false,
        confirmButtonColor: '#7ED32C',
        showLoaderOnConfirm: true,
      },
      function() {
        var type = 0;
        if (tipo_item == 'OrdemDeCompraItem') {
          type = 1;
        }
        if (tipo_item == 'QuadroDeConcorrencia') {
          type = 2;
        }
        if (tipo_item == 'Contrato') {
          type = 3;
        }
        if (tipo_item == 'ContratoItemModificacao') {
          type = 4;
        }

        $.ajax("/notifications/marcar-lido", {
          data: {
            type: type,
            id: item_id
          },
          type: 'POST'
        });

        workflowCall(item_id, tipo_item, aprovou, elemento, null, null, pai_id, pai_obj, filhos_metodo, shouldReload);
      });
  }
}

