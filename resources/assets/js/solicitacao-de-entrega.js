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
    this.btnFinalizar = $('#finalizar');

    this.tables.on(mixInputEvents, '.js-qtd', this.valueChangeHandler.bind(this));
    this.btnFinalizar.on('click', this.save.bind(this));
  },

  valueChangeHandler: function(event) {
    var input = $(event.currentTarget);

    this.updateApropriacoes(input);
    this.updateInputTotal(input);
    this.updateTotal(input);
  },

  updateTotal: function(input) {
    var container = input.parents('.js-table-container:first');

    var totals = _(container.find('.js-total'))
      .map(function(el) {
        return $(el).data('value');
      })
      .filter(Boolean)
      .sum();

    this.totalContainer.text(
      floatToMoney(totals)
    );

    return totals;
  },

  updateInputTotal: function(input) {
    var valuePerUnit = parseFloat(input.data('value-per-unit'));
    var qtd = this.getQtd(input, event.validate);
    var value = qtd * valuePerUnit;
    var total = input.parents('tr:first').find('.js-total');

    total.data('value', value);
    total.prop('innerText', floatToMoney(value));
  },

  updateApropriacoes: function(input) {
    var _this = this;
    var apropriacoes = $('[data-apropriacao="' + input.data('apropriacao') + '"]');

    apropriacoes.each(function(n, el) {
      el.value = input.val();
      _this.updateInputTotal($(el));
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
        text: 'A quantidade solicitada não pode ser maior do que ' +
          'a quantidade contratada',
        type: 'warning'
      });

      input.val(floatToMoney(qtdMax, ''));

      return qtdMax;
    }

    return qtd;
  },

  save: function(event) {
    event.preventDefault();
    event.stopPropagation();

    var inputsQtd = $('.js-qtd');
    var selections = $('.js-selected:not(:empty)');

    var qtds = _(inputsQtd)
      .filter(function(input) {
        return !!input.value.length && (input.value !== '0,00');
      })
      .map(function(input) {
        return {
          apropriacao: _.parseInt(input.dataset.apropriacao),
          contrato_item_id: _.parseInt(input.dataset.contratoItem),
          qtd: moneyToFloat(input.value)
        };
      })
      .uniqBy('apropriacao')
      .value();

    var selected = _(selections)
      .uniqBy('dataset.apropriacao')
      .map(function(container) {
        var trs = $(container).find('tr');

        return _(trs).map(function(tr, n) {
            var inputs = tr.querySelectorAll('input');

            return _.reduce(inputs, function(selected, input) {
              selected[input.name] = input.value;

              return selected;
            }, {
              apropriacao: container.dataset.apropriacao,
              contrato_item_id: container.dataset.contratoItem,
            });

          })
          .value()
      })
      .flatten()
      .value();

    var data = _.flatten([selected, qtds]);

    startLoading();
    $.post(location.href, {solicitacao: data})
      .always(stopLoading)
      .done(function() {
        swal('Solicitação de Entrega', 'Solicitação enviada para aprovação', 'success');
      })
      .fail(function() {
        swal('Solicitação de Entrega', 'Ocorreu um erro ao realizar esta ação', 'error');
      });
  }
};

var ModalSelecionarInsumo = {
  init: function() {
    var _this                    = this;
    this.modal                   = $('#modal-selecionar-insumos');
    this.listaDeTroca            = $('#lista-de-troca');
    this.table                   = this.listaDeTroca.find('table:first');
    this.selectInsumo            = this.createInsumoSelector();
    this.inputQtd                = $('#qtd_total');
    this.inputValorUnitario      = $('#valor_unitario');
    this.btnSaveSelectedInsumos  = $('#save-selected-insumos');

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

    this.createSolicitarInsumoButton();
    this.handleAddButton();
  },

  createInsumoSelector: function() {
    var _this = this;
    var selectInsumo = select2('#insumo_id', {
      url: '/buscar/insumos',
      filter: function(item) {
        var insumos = _this.getSelectedInsumos();

        return !insumos.includes(item.id);
      }
    });

    return selectInsumo;
  },

  getSelectedInsumos: function() {
    return _.map(this.modal.find('.js-added-insumo'), 'value').map(_.ary(parseInt, 1));
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
  insumoTemplate: function(insumo, qtd, valor_unitario) {
    return '<tr>' +
      '<td>' + insumo.codigo + '</td>' +
      '<td>' + insumo.nome + '</td>' +
      '<td>' + insumo.unidade_sigla + '</td>' +
      '<td>' + floatToMoney(qtd, '') + '</td>' +
      '<td>' + floatToMoney(valor_unitario) + '</td>' +
      '<td class="js-selected-total" data-value="' + (valor_unitario * qtd) + '">' + floatToMoney(valor_unitario * qtd) + '</td>' +
      '<td>' +
      '<input class="js-added-insumo" type="hidden" name="insumo" value="' + insumo.id + '">' +
      '<input type="hidden" name="qtd" value="' + qtd + '">' +
      '<input type="hidden" name="valor_unitario" value="' + valor_unitario + '">' +
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
    var valor_unitario = moneyToFloat(this.inputValorUnitario.val());

    // Só pra evitar cagada
    if (!button) {
      throw new Error('Ultimo botão de selecionar insumo clicado não encontrado!');
    }

    startLoading();

    getInsumo(insumo_id)
      .always(stopLoading)
      .done(function(insumo) {
        button.data('insumos', _this.getSelectedInsumos());

        var row = $(_this.insumoTemplate(insumo, qtd, valor_unitario));

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
    this.inputValorUnitario.val('0,00');
    this.inputValorUnitario.blur();
  },

  onModalClose: function() {
    this.save();
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

    if (insumos_html && insumos_html.trim()) {
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

    var total = _(this.table.find('.js-selected-total'))
      .map(function(el) {
        return parseFloat(el.dataset.value);
      })
      .sum();

    button.data('valor', total);
    button.data('insumos', this.getSelectedInsumos());
    button.data('insumos_html', this.table.find('tbody').html());

    SolicitacaoDeEntrega.updateTotal(button);
  },

  save: function() {
    var button = this.lastClickedButton();
    var rows = this.table.find('tbody > tr');

    var total = _(this.table.find('.js-selected-total'))
      .map(function(el) {
        return parseFloat(el.dataset.value);
      })
      .sum();

    button.parents('tr:first')
      .find('.js-total')
      .data('value', total)
      .text(floatToMoney(total));

    button.toggleClass('btn-primary', !rows.length);
    button.toggleClass('btn-success', !!rows.length);

    $('[data-apropriacao="' + button.data('apropriacao') + '"].js-selected')
      .html(button.data('insumos_html'));

    button.text(rows.length ? 'Visualizar Insumos' : 'Selecionar Insumos');

    var totals = SolicitacaoDeEntrega.updateTotal(button);

    button.prop('title', 'Total: ' + floatToMoney(totals))
    button.tooltip();

    this.mirror(button);
    this.modal.modal('hide');
  }
}

