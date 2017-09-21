$(function() {
  var groupSelector = $('.js-group-selector');
  var insumo = document.getElementById('grupos_de_insumos_insumo_id');

  var url = function(type, id, data) {
    data = data ? ('?' + querystring.stringify(data)) : '';

    return '/mascara_padrao_insumos/' + type + '/' + id + data;
  };

  var makeOptions = function(data) {
    return ['<option value=""></option>'].concat(
      _.map(data, function(label, value) {
        return '<option value="'+  value + '">' + label + '</option>';
      })
      .join('')
    );
  };

  groupSelector.on('change', function(event) {
    var selector = event.currentTarget;
    var current = parseInt(selector.dataset.inputOrder, 10);

    if(selector.disabled || current === groupSelector.length || !selector.value) {
      return true;
    }

    startLoading();

    var next = document.querySelector('[data-input-order="' + (current + 1) + '"]');

    _.each(_.range(current + 1, groupSelector.length + 1), function(n) {
      var selector = $('[data-input-order=' + n + ']');

      selector.prop('disabled', true);
      selector.val(null).change();
      selector.html('');
    });

    var request_url = url(
      next.dataset.inputType,
      selector.value,
      // Passa o insumo para filtrar o serviço
      insumo && { insumo_id : insumo.value }
    );

    $.get(request_url)
      .always(stopLoading)
      .done(function(grupos) {
        $(next).html(makeOptions(grupos));
        next.disabled = false;
      })
      .fail(function() {
        swal('', 'Ocorreu um problema ao carregar os filtros...', 'error');
      });
  });
});
