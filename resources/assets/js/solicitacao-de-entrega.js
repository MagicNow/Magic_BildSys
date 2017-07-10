$(function() {
  ViewChanger.init();
  SolicitacaoDeEntrega.init();
  ModalSelecionarInsumo.init();

  $('#cancel').on('click', function(event) {
    var button = event.currentTarget;
    swal({
      title: 'Cancelar solicitação de entrega?',
      text: 'Ao confirmar não será possível voltar atrás',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Cancelar',
      cancelButtonText: 'Não',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, function() {
      $.post('/solicitacoes-de-entrega/' + button.dataset.id + '/cancelar')
        .done(function() {
          swal({
            title: 'Pronto!',
            text: 'Solicitaçao de entrega cancelada com sucesso',
            type: 'success'
          }, function() {
            location.href = '/solicitacoes-de-entrega/' + button.dataset.id;
          });
        })
        .fail(function() {
          swal(
            'Ops!',
            'Não foi possivel cancelar esta solicitação de entrega!',
            'error'
          );
        })
    });
  });

  $('.js-fornecedor-selector').on('view-changer:change', function(event) {
    var $current = $(event.currentTarget);
    $('#fornecedor-selector').toggleClass('hidden', $current.val() !== 'direto');

  });

  $('.js-view-selector').on('view-changer:change', function(event) {
    $('.js-extra-info').each(function() {
      if($(this).parents('tr').next().is(':visible')) {
        this.click();
      }
    });
  });

  $('.js-fornecedor-selector').on('view-changer:hidden', function(event) {
    var $current = $(event.currentTarget);
    var $view = $('[data-view-name=' + $current.val() + ']');

    $view.find('input').each(function() {
      $(this).val('').trigger('change');
    });
  });

  var $selectFornecedor = $('#fornecedor_id');
  select2($selectFornecedor, {url: '/buscar/fornecedores?ignore[]=' + $selectFornecedor.data('ignore')});
});

var mixInputEvents = 'input keyup blur change';

var focusAtTheEnd = function(input) {
  var temp = input.val();
  input.val('').focus().val(temp);
};


var ViewChanger = {
  init: function() {
    var $selectors = $('.js-view-selector');

    $selectors.on('ifChecked', function(event) {
      var $container = $(event.currentTarget.dataset.container || document.body);
      var $current = $(event.currentTarget);
      var $inputs = $('[name="' + event.currentTarget.name + '"]');

      var hiddenInputs = _.filter($inputs.each(function(n, el) {
        $container.find('[data-view-name="' + el.value + '"]').hide();
      }), function(el) {
        return el.value !== $current.val();
      });

      $container
        .find('[data-view-name="' + event.currentTarget.value + '"]')
        .fadeIn();

      hiddenInputs.map(function(el) {
        $(el).trigger('view-changer:hidden');
      });

      $current.trigger('view-changer:change');
    });

    $selectors.filter(':checked').each(function() {
      $(this).trigger('ifChecked');
    });
  }
};

var SolicitacaoDeEntrega = {
  init: function() {
    var editing = document.getElementById('is-editing');
    this.isEditing = editing && (editing.value === '1');
    this.tables = $('.js-table-container');
    this.totalContainer = $('#total-container');
    this.btnFinalizar = $('#finalizar');


    this.tables.on('blur', '.js-new-value', function(event) {
      var input = $(event.currentTarget);

      if(!input.val().length || input.val() === '0,00') {
        event.preventDefault();
        event.stopPropagation();

        input.val(input.data('initial-value')).trigger('change');

        swal({
          title: 'Valor Inválido',
          text: 'O valor do insumo não pode ser 0,00',
          type: 'warning'
        }, function() {
          focusAtTheEnd(input);
        });
      }
    });

    if(this.isEditing) {
      this.tables.on(
        'blur',
        '.js-qtd',
        this.validateZerosOnEdition('A nova quantidade não pode ser 0,00')
      );

      this.tables.on(
        'blur',
        '.js-new-value',
        this.validateZerosOnEdition('O novo valor unitário não pode ser R$ 0,00')
      );

      this.tables.on(
        mixInputEvents,
        '.js-new-value',
        this.newValueChangeHandler.bind(this)
      );
    }

    this.tables.on(mixInputEvents, '.js-qtd', this.qtdChangeHandler.bind(this));

    this.btnFinalizar.on('click', this.save.bind(this));
  },

  validateZerosOnEdition: function(message) {
    var handler = function(event) {
      var input = $(event.currentTarget);

      if(!input.val().length || input.val() === '0,00') {
        event.preventDefault();
        event.stopPropagation();

        input.val(input.data('initial-value')).trigger('change');

        swal({
          title: 'Valor Inválido',
          text: message,
          type: 'warning'
        }, function() {
          focusAtTheEnd(input);
        });
      }
    };

    return handler.bind(this);
  },

  qtdChangeHandler: function(event) {
    var input = $(event.currentTarget);

    this.updateApropriacoes(input);
    this.updateInputTotal(input);
    this.updateTotal(input);
  },

  newValueChangeHandler: function(event) {
    var input = $(event.currentTarget);

    var qtd = $('[data-apropriacao="' + input.data('apropriacao') + '"].js-qtd');

    this.updateApropriacoes(input, '.js-new-value', false);

    qtd.each(function() {
      $(this)
        .data('value-per-unit', moneyToFloat(input.val()))
        .trigger('change');
    });
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

  updateApropriacoes: function(input, type, updateTotal) {
    var _this = this;
    type = type || '.js-qtd';
    updateTotal = _.isUndefined(updateTotal) ? true : updateTotal;

    var apropriacoes = $(
      '[data-apropriacao="' + input.data('apropriacao') + '"]' + type
    );

    apropriacoes.each(function(n, el) {
      el.value = input.val();
      if(updateTotal) {
        _this.updateInputTotal($(el));
      }
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
    var selectedType = $('.js-fornecedor-selector:checked');
    var newValues = $('.js-new-value');
    var fornecedor = $('#fornecedor_id');
    var formData = {};

    if(selectedType.val() === 'direto' && !fornecedor.val()) {
      swal({
        title: 'Solicitação de Entrega',
        text: 'Selecione o fornecedor do faturamento direto',
        type: 'warning'
      }, function() {
        fornecedor.select2('open');
      });
      return false;
    }

    if(fornecedor.val()) {
      formData.fornecedor_id = fornecedor.val();
    }

    if(this.isEditing) {
      var inputs = $('[data-initial-value]');

      var nothingHasChanged = _.reduce(inputs, function(nothingHasChanged, input) {
        return nothingHasChanged && (input.value.trim() === input.dataset.initialValue.trim());
      }, true);

      if(nothingHasChanged) {
        swal({
          title: 'Solicitaçao de Entrega',
          type: 'warning',
          text: 'Nenhuma alteração foi encontrada'
        });

        return false;
      }
    }

    var qtds = _(inputsQtd)
      .filter(function(input) {
        return !!input.value.length && (input.value !== '0,00');
      })
      .map(function(input) {
        return {
          apropriacao: _.parseInt(input.dataset.apropriacao),
          contrato_item_id: input.dataset.contratoItem || null,
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

    var newValuesObjects = _(newValues)
      .uniqBy('dataset.apropriacao')
      .map(function(newValue) {
        return {
            apropriacao: newValue.dataset.apropriacao,
            value: moneyToFloat(newValue.value),
        };
      })
      .value();

    formData.solicitacao = _.flatten([selected, qtds, newValuesObjects]);

    if(!formData.solicitacao.length) {
      swal({
        title: 'Solicitação de Entrega',
        text: 'Nenhuma solicitação foi feita',
        type: 'warning'
      });

      return false;
    }

    startLoading();

    $.ajax({
      url: location.href.replace('/edit', ''),
      data: formData,
      method: this.isEditing ? 'PATCH' : 'POST',
    })
      .always(stopLoading)
      .done(function() {
        swal({
          title: 'Solicitação de Entrega',
          text: 'Solicitação enviada para aprovação',
          type: 'success'
        }, function() {
          location.href = location.href
            .replace('/solicitar-entrega', '')
            .replace('/edit', '');
        });
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
      .on('click', '.js-remove-row', this.removeInsumo.bind(this))
      .on('show.bs.modal', this.addHideEventHandlerToModal.bind(this));


    this.btnSaveSelectedInsumos.on('click', this.save.bind(this));

    this.createSolicitarInsumoButton();
    this.handleAddButton();
  },

  addHideEventHandlerToModal: function() {
    this.modal.one('hide.bs.modal', this.onModalClose.bind(this))
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

  onModalClose: function(event) {
    var _this = this;
    var total = this.getTotal();
    var max = this.lastClickedButton().data('valor-max');

    if(total > max) {
      event.preventDefault();
      swal({
        title: '',
        text: 'A seleção de insumos ultrapassa o orçamento de ' +
        floatToMoney(max) + ' desta apropriação',
        type: 'warning'
      }, function() {
        _this.addHideEventHandlerToModal();
      });

      return false;
    }

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

  getTotal: function() {
    return _(this.table.find('.js-selected-total'))
      .map(function(el) {
        return parseFloat(el.dataset.value);
      })
      .sum();
  },

  save: function() {
    var button = this.lastClickedButton();
    var rows = this.table.find('tbody > tr');

    var total = this.getTotal();

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

