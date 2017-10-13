(function($) {
  $(function() {
    $('#fechar-qc').on('click', fecharQcClickHandler);
  });

  function fecharQcClickHandler(event) {
    var button = event.currentTarget;
    var id = button.dataset.id;

    swal({
      type: 'warning',
      title: 'Fechar Q.C. Avulso #' + button.dataset.id,
      text: 'Tem certeza que deseja fechar este Q.C.? Esta operação não pode ser desfeita.',
      showCancelButton: true,
      confirmButtonText: 'Finalizar',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, function() {
      $.post('/qc/' + id + '/fechar')
        .done(function() {
          swal({
            type: 'success',
            title: 'Sucesso!',
            text: 'Q.C. Fechado com sucesso',
            showLoaderOnConfirm: true,
          }, function() {
            location.reload();
          })
        })
        .fail(function() {
          swal({
            type: 'error',
            title: 'Ops!',
            text: 'Não foi possível realizar operação. Tente tente novamente.',
          });
        })
    });
  }
}(jQuery));
