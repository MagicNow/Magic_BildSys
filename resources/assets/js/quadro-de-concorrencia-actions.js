(function($) {
  const $attachments = $('.qc-anexos');
  const $attachmentsFields = $attachments.find('.qc-anexos-campos').first().clone();
  const baseUrl = $('body').attr('baseurl');
  const MAX_FILE_SIZE_MB = 20;

  $(function () {
    var obraId = $('#obra_id');
    select2('#carteira_id', { url: baseUrl + '/buscar/qc-avulso-carteiras',
      filter: function(carteira) {

        if(obraId.val()) {
          var obras = carteira
            .tarefas
            .map(_.property('obra_id'));

          return carteira.tarefas.length && obras.includes(parseInt(obraId.val()));
        }

        return true;
      }
    });

    $attachments.find('input[type="file"]').on('change', checkAttachmentTypeExists);
    $attachments.find('select').on('change', checkAttachmentTypeExists);

    $attachments.on('click', '.js-qc-anexos-novo', addAttachmentRow);
    $attachments.on('click', '.js-qc-anexos-remover', removeAttachmentRow);
    $attachments.on('change', '.form-control', checkAttachmentTypeExists);
    $attachments.on('change', 'input[type="file"]', checkFileSize);

    obraId.on('change', function(e) {
      $('#carteira_id').val(null).change();
    });

    $('#save-qc').on('click', function(event) {
      var $qcObrigatorio = $('#qc-obrigatorio');

      if(!$qcObrigatorio.val()) {
        event.preventDefault();
        event.stopPropagation();

        swal('Atenção!', 'É obrigatório anexar um arquivo de Q.C.', 'warning');
      }

    });

    $('button[type=submit]').prop('disabled', false);
  });

  function removeAttachmentRow(e) {
    $(e.currentTarget).parents('.qc-anexos-campos:first').remove();
    $attachments.find('.js-qc-anexos-remover:last').addClass('hidden');
    $attachments.find('.js-qc-anexos-novo:last').removeClass('hidden');
  }

  function addAttachmentRow (e) {
    e.preventDefault();

    const $self = $(this);

    let $attachmentsFieldsNew;
    let $attachmentsFieldsNewButton;

    $self
      .addClass('hidden')
      .parents('.qc-anexos-campos')
      .find('.js-qc-anexos-remover')
      .removeClass('hidden')

    $self
      .parents('.qc-anexos-campos')
      .find('.form-control')
      .addClass('.readonly')
      .attr('readonly', 'readonly')
      .attr('tabindex', '-1')
      .attr('attr-disabled', 'true');


    $attachments.append($attachmentsFields.clone());

    $attachmentsFieldsNew = $attachments.find('.qc-anexos-campos:last').last();
    $attachmentsFieldsNew.find('select').prop('selectedIndex', 0);
    $attachmentsFieldsNew.find('input').val('');

    $attachmentsFieldsNew
      .find('.js-qc-anexos-novo')
      .removeClass('hidden')
      .prop('disabled', true);

    $attachmentsFieldsNew
      .find('.js-qc-anexos-remover')
      .addClass('hidden');
  }

  function checkFileSize(e) {
    var files = e.currentTarget.files;

    if(files.length && (files[0].size / 1024 / 1024) > 20) {
      swal('Atenção!', 'O tamanho máximo de upload é 20mb por arquivo!', 'warning');
      $(e.currentTarget)
        .val('')
        .prop('type', 'text')
        .prop('type', 'file')
        .trigger('change');
    }
  }

  function checkAttachmentTypeExists (e) {
    const $self = $(this);
    const $attachmentsFields = $self.parents('.qc-anexos-campos');
    const $attachmentsFieldsSelect = $attachmentsFields.find('select');
    const $attachmentsFieldsFile = $attachmentsFields.find('input[type="file"]');
    const $attachmentsFieldsButton = $attachmentsFields.find('.js-qc-anexos-novo');

    let qc = $attachments.find('select')
      .filter(function(i, el) {
        return el.value === 'Quadro de concorrência'
      })
      .size();

    if ($attachmentsFieldsFile.val() !== '' && $attachmentsFieldsSelect.val() !== '' && qc > 1) {
      e.stopPropagation();

      swal('Atenção!', 'Você só pode anexar um arquivo de Quadro de Concorrência', 'warning');
      $attachmentsFieldsSelect.prop('selectedIndex', 0);
      $attachmentsFieldsFile
        .val('')
        .prop('type', 'text')
        .prop('type', 'file')
        .trigger('change');

    }

    if ($attachmentsFieldsFile.val() !== '' && $attachmentsFieldsSelect.val() !== '' && qc <= 1) {
      $attachmentsFieldsButton.prop('disabled', false);
    }

    if(!$attachmentsFieldsFile.val()) {
      $attachmentsFieldsButton.prop('disabled', true);
    }
  }
}(jQuery));
