const $attachments = $('.qc-anexos');
const baseUrl = $('body').attr('baseurl');

$(function () {
  select2('#carteira_id', {
    url: baseUrl + "/buscar/carteiras",
  })

	$attachments.find('input[type="file"]').on('change', checkAttachmentTypeExists);
	$attachments.find('select').on('change', checkAttachmentTypeExists);
	$attachments.on('click', '.qc-anexos-novo', addAttachmentRow);
});

function addAttachmentRow (e) {
	e.preventDefault();

	const $self = $(this);
	const $attachmentsFields = $attachments.find('.qc-anexos-campos').first();
	let $attachmentsFieldsNew;
	let $attachmentsFieldsNewButton;

	$self.hide();
	$attachments.append($attachmentsFields.clone());
	$attachmentsFieldsNew = $attachments.find('.qc-anexos-campos').last();
	$attachmentsFieldsNewButton = $attachmentsFieldsNew.find('.qc-anexos-novo')

	$attachmentsFieldsNew.find('select').prop('selectedIndex', 0);
	$attachmentsFieldsNew.find('input').val('');
	$attachmentsFieldsNewButton.show().attr('disabled', 'disabled');
	$attachmentsFieldsNew.find('input[type="file"]').on('change', checkAttachmentTypeExists);
	$attachmentsFieldsNew.find('select').on('change', checkAttachmentTypeExists);
}

function checkAttachmentTypeExists () {
	const $self = $(this);
	const $attachmentsFields = $self.parents('.qc-anexos-campos');
	const $attachmentsFieldsSelect = $attachmentsFields.find('select');
	const $attachmentsFieldsFile = $attachmentsFields.find('input[type="file"]');
	const $attachmentsFieldsButton = $attachmentsFields.find('.qc-anexos-novo');

	let qc = 0;

	$attachments.find('select').each(function(i, el) {
		if (el.value == 'Quadro de concorrência') {
			qc++;
		}
	});

	if ($attachmentsFieldsFile.val() !== '' && $attachmentsFieldsSelect.val() !== '' && qc <= 1) {
		$attachmentsFieldsButton.removeAttr('disabled');
	} else {
		if ($attachmentsFieldsFile.val() !== '' && $attachmentsFieldsSelect.val() !== '' && qc > 1) {
			alert('Somente é possível anexar um Q.C.');
			$attachmentsFieldsSelect.prop('selectedIndex', 0);
		}
	}
}
