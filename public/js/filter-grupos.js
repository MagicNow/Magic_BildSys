$(function() {
  var groupSelector = $('.js-group-selector');

  var url = function(type, id) {
    return '/ordens-de-compra/' + type + '/' + id;
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

    if(selector.disabled || current === groupSelector.length) {
      return true;
    }

    startLoading();

    var next = document.querySelector('[data-input-order="' + (current + 1) + '"]');

    _.each(_.range(current + 1, groupSelector.length), function(n) {
      var selector = $('[data-input-order=' + n + ']');
      selector.prop('disabled', true);
      selector.val(null).change();
      selector.html('');
    });

    $.get(url(next.dataset.inputType, selector.value))
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
