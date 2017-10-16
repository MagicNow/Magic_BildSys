function select2(selector, options) {
  options = options || {};
  options = Object.assign({
    allowClear: true,
    placeholder: 'Escolha...',
    language: "pt-BR",
    theme: 'bootstrap',
    ajax: {
      url: options.url || (options.ajax ? options.ajax.url : ''),
      dataType: 'json',
      delay: 250,

      data: function(params) {
        return {
          q: params.term,
          page: params.page,
        };
      },

      processResults: function(result, params) {
        params.page = params.page || 1;

        return {
          results: result.data.filter(options.filter || Boolean),
          pagination: {
            more: (params.page * result.per_page) < result.total
          }
        };
      },
      cache: true
    },
    escapeMarkup: function(markup) {
      return markup;
    },
    minimumInputLength: 1,
    templateResult: formatResult,
    templateSelection: formatResultSelection
  }, options)

  return $(selector).select2(options);
}

function formatResultSelection(obj) {
  if (obj.nome) {
    return obj.nome;
  }
  return obj.text;
}

function formatResult(obj) {
  if (obj.loading) return obj.text;

  var markup = "<div class='select2-result-obj clearfix'>" +
    "   <div class='select2-result-obj__meta'>" +
    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
    "   </div>" +
    "</div>";

  return markup;
}
