(function($) {
  var inputValorFechamento = $('#valor_fechamento');
  var inputContrato = $('#numero_contrato_mega');
  var inputFornecedor = $('#fornecedor_id');
  var inputQcFile = $('#qc-fechado');

  $(function() {
    $('#fechar-qc').on('click', fecharQcClickHandler);

    select2('#fornecedor_id', {
      url: '/buscar/fornecedores',
      placeholder: 'Selecionar fornecedor...'
    });
  });

  function validate() {
    var validation = {
      hasError: false,
      errors: []
    };

    if(!inputValorFechamento.val() || inputValorFechamento.val() === '0,00') {
      validation.errors.push('Preencha o valor do fechamento');
    }

    if(!inputContrato.val()) {
      validation.errors.push('Preencha o número do contrato MEGA');
    }

    if(!inputQcFile.val()) {
      validation.errors.push('Envie o arquivo do Q.C. fechado');
    }

    if(!inputFornecedor.val()) {
      validation.errors.push('Selecione o fornecedor do Q.C.');
    }

    validation.hasError = !!validation.errors.length;

    return validation;
  }

  function fecharQcClickHandler(event) {
    var validation = validate();
    var button = event.currentTarget;
    var id = button.dataset.id;

    if(validation.hasError) {
      var errors = new ErrorList(validation.errors);

      return swal({
        type: 'error',
        title: 'Verifique os itens abaixo:',
        text: errors.toHTML(),
        customClass: 'custom-alert',
        html: true,
      });
    }

    var data = new FormData(inputValorFechamento.prop('form'));

    data.delete('_method');

    swal({
      type: 'warning',
      title: 'Fechar Q.C. Avulso #' + button.dataset.id,
      text: 'Tem certeza que deseja fechar este Q.C.? Esta operação não pode ser desfeita.',
      showCancelButton: true,
      confirmButtonText: 'Fechar Q.C.',
      cancelButtonText: 'Não',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, function() {
      $.ajax({
        url: '/qc/' + id + '/fechar',
        method: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
      })
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
