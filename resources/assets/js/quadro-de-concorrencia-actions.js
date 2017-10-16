(function($) {
  const $attachments = $('.qc-anexos');
  const $attachmentsFields = $attachments.find('.qc-anexos-campos').first().clone();
  const baseUrl = $('body').attr('baseurl');
  const MAX_FILE_SIZE_MB = 20;

  $(function () {
    select2('#carteira_id', {
      url: baseUrl + "/buscar/carteiras",
    })

    $attachments.find('input[type="file"]').on('change', checkAttachmentTypeExists);
    $attachments.find('select').on('change', checkAttachmentTypeExists);

    $attachments.on('click', '.js-qc-anexos-novo', addAttachmentRow);
    $attachments.on('click', '.js-qc-anexos-remover', removeAttachmentRow);
    $attachments.on('change', '.form-control', checkAttachmentTypeExists);
    $attachments.on('change', 'input[type="file"]', checkFileSize);
  });

  function removeAttachmentRow(e) {
    $(e.currentTarget).parents('.qc-anexos-campos:first').remove();
    $attachments.find('.js-qc-anexos-remover:last').prop('disabled', true);
    $attachments.find('.js-qc-anexos-novo:last').show();
  }

  function addAttachmentRow (e) {
    e.preventDefault();

    const $self = $(this);

    let $attachmentsFieldsNew;
    let $attachmentsFieldsNewButton;

    $self
      .hide()
      .parents('.qc-anexos-campos')
      .find('.js-qc-anexos-remover')
      .prop('disabled', false);

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
      .show()
      .prop('disabled', true);

    $attachmentsFieldsNew
      .find('.js-qc-anexos-remover')
      .prop('disabled', true);
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

  function checkAttachmentTypeExists () {
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
