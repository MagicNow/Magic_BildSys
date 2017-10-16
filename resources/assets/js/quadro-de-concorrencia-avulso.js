function cancelarQC(qual) {
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

  }, function() {
    $.ajax("/qc/" + qual + "/cancelar").done(function(retorno) {
      var texto = '';

      if (retorno.mensagens.length) {
        $.each(retorno.mensagens, function(n, elem) {
          texto = texto + elem + "\n";
        });
      }

      swal({
        title: 'Quadro de Concorrência cancelado!',
        text: texto,
        type: 'success'

      }, function() {
        if (window.LaravelDataTables === undefined)
           location.reload()
        else
           window.LaravelDataTables["dataTableBuilder"].draw();
      });

    }).fail(function(jqXHR, textStatus, errorThrown) {
          var textResponse = jqXHR.responseText;
          var alertText = "Confira as mensagens abaixo:\n\n";
          var jsonResponse = jQuery.parseJSON(textResponse);

          $.each(jsonResponse, function(n, elem) {
            alertText = alertText + elem + "\n";

          });
          swal('Erro', alertText, 'error');

        });


  });

}
