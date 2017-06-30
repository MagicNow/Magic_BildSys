$(function() {
  ViewChanger.init();
  SolicitacaoDeEntrega.init();
  ModalSelecionarInsumo.init();
});

var mixInputEvents = 'input keyup blur change';

var ViewChanger = {
  init: function() {
    var $selectors = $('.js-view-selector');

    $selectors.on('ifChecked', function(event) {
      $('[data-view-name]').hide();
      $('[data-view-name="' + event.currentTarget.value + '"]').fadeIn();
    });
  }
};

var SolicitacaoDeEntrega = {
  init: function() {
    this.tables = $('.js-table-container');
    this.totalContainer = $('#total-container');

    this.tables.on(mixInputEvents, '.js-qtd', this.valueChangeHandler.bind(this));
  },

  valueChangeHandler: function(event) {
    var input = $(event.currentTarget);
    var container = input.parents('.js-table-container');
    var total = input.parents('tr:first').find('.js-total');
    var qtd = this.getQtd(input, event.validate);
    var valuePerUnit = parseFloat(input.data('value-per-unit'));
    var value = qtd * valuePerUnit;

    if (event.updateApropriacoes !== false) {
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

  updateGeneralTotal: function() {}
};

var ModalSelecionarInsumo = {
  init: function() {
    var _this = this;
    this.modal = $('#modal-selecionar-insumos');
    this.listaDeTroca = $('#lista-de-troca');
    this.table = this.listaDeTroca.find('table:first');
    this.selectInsumo = this.createInsumoSelector();
    this.inputQtd = $('#qtd_total');
    this.btnSaveSelectedInsumos = $('#save-selected-insumos');
    this.btnClearSelectedInsumos = $('#clear-selected-insumos');

    $body.on('click', '.js-selecionar-insumo', function(event) {
      var button = $(event.currentTarget);
      _this.modal
        .modal('show')
        .data('btnSelecionarInsumo', button);

      _this.onModalOpen();
    });

    this.modal
      .on('hidden.bs.modal', this.onModalClose.bind(this))
      .on('click', '.js-remove-row', this.removeInsumo.bind(this));

    this.btnSaveSelectedInsumos.on('click', this.save.bind(this));
    this.btnClearSelectedInsumos.on('click', this.clear.bind(this));

    this.createSolicitarInsumoButton();
    this.handleAddButton();
  },

  createInsumoSelector: function() {
    var _this = this;
    var selectInsumo = select2('#insumo_id', {
      url: '/buscar/insumos',
      filter: function(item) {
        var button = _this.lastClickedButton();
        var insumos = button.data('insumos') || [];

        return !insumos.includes(item.id);
      }
    });

    return selectInsumo;
  },

  getSelectedInsumos: function() {
    return _.map($('.js-added-insumo'), 'value').map(_.ary(parseInt, 1));
  },

  createSolicitarInsumoButton: function() {
    var btnSolicitarInsumo = $('#solicitar-insumo');

    btnSolicitarInsumo.on('click', function(event) {
      colorbox({
          href: '/solicitar-insumo?is_modal=1&bind_form=0'
        })
        .then(function(iframe) {
          iframe.element.addEventListener('load', function(e) {
            $(e.currentTarget.contentWindow.document)
              .on('click', '#cancel', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $.colorbox.close();
              });
          });
        });
    });

    return btnSolicitarInsumo;
  },

  handleAddButton: function() {
    var _this = this;

    var btnAddInsumo = $('#add-to-list');

    btnAddInsumo.on('click', this.addInsumo.bind(this));
    this.modal.on(mixInputEvents, '.js-input', function(event) {

      var inputs = _this.modal.find('.js-input');

      var isAllValid = _.reduce(inputs, function(isAllValid, input) {
        return isAllValid && (!!input.value.length && input.value !== '0,00');
      }, true);

      // Enabled only if valid data was inputed
      btnAddInsumo.prop('disabled', !isAllValid);

      if (event.keyCode && event.keyCode === 13 && isAllValid) {
        event.stopPropagation();
        btnAddInsumo.click();
      }

    });
  },
  insumoTemplate: function(insumo, qtd) {
    return '<tr>' +
      '<td>' + insumo.codigo + '</td>' +
      '<td>' + insumo.nome + '</td>' +
      '<td>' + insumo.unidade_sigla + '</td>' +
      '<td>' + floatToMoney(qtd, '') + '</td>' +
      '<td>' +
      '<input class="js-added-insumo" type="hidden" name="insumo" value="' + insumo.id + '">' +
      '<input type="hidden" name="qtd_total" value="' + qtd + '">' +
      '<button class="js-remove-row btn btn-xs btn-flat btn-danger">' +
      '<i class="fa fa-trash fa-fw"></i>' +
      '</button>' +
      '</td>' +
      '</tr>';
  },

  addInsumo: function() {
    var _this = this;
    var button = _this.lastClickedButton();
    var insumo_id = this.selectInsumo.val();
    var qtd = moneyToFloat(this.inputQtd.val());

    // Só pra evitar cagada
    if (!button) {
      throw new Error('Ultimo botão de selecionar insumo clicado não encontrado!');
    }

    startLoading();

    getInsumo(insumo_id)
      .always(stopLoading)
      .done(function(insumo) {
        button.data('insumos', _this.getSelectedInsumos());

        var row = $(_this.insumoTemplate(insumo, qtd));
        _this.table.find('tbody').append(row);
        _this.listaDeTroca.removeClass('hidden');
        _this.clearInputs();

        button.data('insumos_html', _this.table.find('tbody').html());
      })
      .fail(function() {
        swal(
          'Selecionar Insumo',
          'Ocorreu um erro ao selecionar o insumo!',
          'error'
        );
      });
  },

  clearSelectedInsumos: function() {
    this.listaDeTroca.addClass('hidden');
    this.table.find('tbody').html('');
  },

  clearInputs: function() {
    this.selectInsumo.val(null).trigger('change');
    this.inputQtd.val('0,00');
    this.inputQtd.blur();
  },

  onModalClose: function() {
    this.clearSelectedInsumos();
    this.clearInputs();
  },

  mirror: function(button) {
    var clone = button.clone(true, true);
    var apropriacao = button.data('apropriacao');

    clone.replaceAll('[data-apropriacao="' + apropriacao + '"].js-selecionar-insumo');
  },

  onModalOpen: function() {
    var button = this.lastClickedButton();
    var insumos_html = button.data('insumos_html');

    if (insumos_html) {
      this.table.find('tbody').html(insumos_html);
      this.listaDeTroca.removeClass('hidden');
    }
  },

  lastClickedButton: function() {
    return this.modal.data('btnSelecionarInsumo');
  },

  removeInsumo: function(event) {
    $(event.currentTarget).parents('tr').remove();
    var button = this.lastClickedButton();
    var rows = this.table.find('tbody > tr');

    if (!rows.length) {
      this.listaDeTroca.addClass('hidden');
    }

    button.data('insumos', this.getSelectedInsumos());
    button.data('insumos_html', this.table.find('tbody').html());
  },

  clear: function() {
    var _this = this;

    if(!this.table.find('tbody > tr').size()) {
      swal('Selecionar Insumos', 'Lista de insumos vazia!', 'warning');

      return false;
    }

    swal({
        title: 'Limpar seleção',
        text: 'Tem certeza que deseja limpar a seleção de insumos?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Limpar Seleção',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
        confirmButtonColor: '#7ED32C'
      },
      function() {
        var button = _this.lastClickedButton();

        button.data('insumos', []);
        button.data('insumos_html', '');
        button.removeClass('btn-success').addClass('btn-primary');

        button.text('Slecionar Insumos');

        _this.mirror(button);

        _this.modal.modal('hide');
      }
    );

  },

  save: function() {
    var button = this.lastClickedButton();
    var rows = this.table.find('tbody > tr');

    if (!rows.length) {
      swal('Selecionar Insumos', 'Lista de insumos vazia!', 'warning');

      return false;
    }

    button.removeClass('btn-primary').addClass('btn-success');

    $('[data-apropriacao="' + button.data('apropriacao') + '"].js-selected')
      .html(button.data('insumos_html'));

    button.text('Visualizar Insumos');

    this.mirror(button);

    this.modal.modal('hide');
  }
}

