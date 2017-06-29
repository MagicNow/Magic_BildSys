$(function() {
  ViewChanger.init();
  SolicitacaoDeEntrega.init();
});

var ViewChanger = {
  init: function() {
    var $selectors = $('.js-view-selector');

    $selectors.on('ifChecked', function(event) {
      $('[data-view-name]').hide();
      $('[data-view-name="' + event.currentTarget.value + '"]').fadeIn();
    });
  }
}

var SolicitacaoDeEntrega = {
  init: function() {
    this.tables = $('.js-table-container');
    this.totalContainer = $('#total-container');

    this.tables.on(
      'keyup keydown keymap change blur',
      '.js-qtd',
      this.valueChangeHandler.bind(this)
    );
  },

  valueChangeHandler: function(event) {
    var input        = $(event.currentTarget);
    var container    = input.parents('.js-table-container');
    var total        = input.parents('tr').find('.js-total');
    var qtd          = this.getQtd(input, event.validate);
    var valuePerUnit = parseFloat(input.data('value-per-unit'));
    var value        = qtd * valuePerUnit;

    if(event.updateApropriacoes !== false) {
      this.updateApropriacoes(input);
    }

    total.data('value', value);
    total.prop('innerText', floatToMoney(value));

    var totals = _(container.find('.js-total'))
      .map(function(el) {
        return $(el).data('value');
      })
      .filter(Boolean)
      .sum();

    this.totalContainer.text(
      floatToMoney(totals)
    );
  },

  updateApropriacoes: function(input) {
    var apropriacoes = $('[data-apropriacao="' + input.data('apropriacao') + '"]');
    var event = $.Event('change');

    event.validate = false;
    event.updateApropriacoes = false;

    apropriacoes.each(function(n, el) {
      el.value = input.val();

      $(el).trigger(event);
    });
  },

  getQtd: function(input, validate) {
    var qtdMax = parseFloat(input.data('qtd-max'));
    validate = validate !== false;

    var qtd = input.val() ?
      moneyToFloat(input.val()) :
      0;

    if (validate && (qtd > qtdMax)) {
      event.stopPropagation();
      event.preventDefault();

      swal({
        title: 'Valor Inválido',
        text: 'A quantidade solicitade não pode ser maior do que ' +
        'a quantidade contratada',
        type: 'warning'
      });

      input.val(floatToMoney(qtdMax, ''));

      return qtdMax;
    }

    return qtd;
  },

  updateGeneralTotal: function() {
  }
};

