const $attachments = $('.qc-anexos');

$(function () {
	$('#carteira_id').select2({
		theme:'bootstrap',
		allowClear: true,
		placeholder: "Escolha...",
		language: "pt-BR",

		ajax: {
			url: "/buscar/carteiras",
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

	$('.currency').on('blur', function () {
		// $(this).val(floatToMoney($(this).val()));
	});

	$attachments.find('input[type="file"]').on('change', checkAttachmentTypeExists);
	$attachments.find('select').on('change', checkAttachmentTypeExists);

	$attachments.on('click', '.qc-anexos-novo', addAttachmentRow);
});

function formatResultSelection (obj) {
	if(obj.nome){
		return obj.nome;
	}
	return obj.text;
}

function formatResult (obj) {
	if (obj.loading) return obj.text;

	var markup_insumo =    "<div class='select2-result-obj clearfix'>" +
			"   <div class='select2-result-obj__meta'>" +
			"       <div class='select2-result-obj__title'>" + obj.nome + "</div>"+
			"   </div>"+
			"</div>";

	return markup_insumo;
}

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
//# sourceMappingURL=qc-actions.js.map
