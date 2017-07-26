$(function() {
  $.fn.modal.Constructor.prototype.enforceFocus = $.noop;

  Reajuste.init();
  Distrato.init();
  Reapropriar.init();
  Editar.init();

  var workflowTipo = $('[data-workflow-tipo]');

  workflowTipo.tooltip({
    title: 'Clique para ver detalhes desta alcada',
    container: document.body
  });

  workflowTipo.on('click', function(event) {
    startLoading();
    $.get('/workflow/detalhes', event.currentTarget.dataset)
      .always(stopLoading)
      .done(function(data) {
        $('#modal-alcadas').html(data);
        $('#modal-alcadas').modal('show');
      })
      .fail(function() {
        swal('Ops!', 'Ocorreu um erro ao mostrar os detalhes da alçada', 'error');
      });
  });

  var obsAprovador = document.getElementById('obs-aprovador');

  if(obsAprovador) {
    var contrato_id = document.getElementById('contrato_id');
    var user_id = document.getElementById('user_id');

    var key = 'contrato_obs_' + user_id.value + '_' + contrato_id.value;

    obsAprovador.value = localStorage.getItem(key);

    var saveObs = _.debounce(function(event) {
      localStorage.setItem(key, obsAprovador.value);
    }, 700);

    obsAprovador.addEventListener('input', saveObs);
    obsAprovador.addEventListener('change', saveObs);
  }


  if (typeof LaravelDataTables !== 'undefined') {
    var table = LaravelDataTables.dataTableBuilder

    var visible = false;

    table.on('init', function() {
      table.button().add(5, {
        action: function(e, dt, button, config) {
          visible = !visible;

          table.column('aliq_irrf:name').visible(visible);
          table.column('aliq_inss:name').visible(visible);
          table.column('aliq_pis:name').visible(visible);
          table.column('aliq_cofins:name').visible(visible);
          table.column('aliq_csll:name').visible(visible);

          button[0].innerHTML = !visible ?
            '<i class="fa fa-money"></i> Exibir Impostos' :
            '<i class="fa fa-money"></i> Ocultar Impostos';
        },
        text: '<i class="fa fa-money"></i> Exibir Impostos'
      });
    });
  }

});

var token = document.currentScript.dataset.token;

var getInputs = _.partial(_.reduce, _, function(data, grupo) {
  data[grupo.name] = grupo.value;
  return data;
});

var getView = function(id, view, modal) {
  startLoading();
  return $.get('/contratos/apropriacoes/' + id, {
      view: view
    })
    .always(stopLoading)
    .done(function(html) {
      $(modal).find('.js-ajax-container').html(html);
      $(modal).modal('show');
      $(modal).find('input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
      });
      $('.money').maskMoney({
        allowNegative: true,
        thousands: '.',
        decimal: ','
      });
    });
};

var Reapropriar = (function() {
  function Reapropriar() {
    this.modal      = document.getElementById('modal-reapropriar');
    this.insumo     = document.getElementById('insumo_id');
    this.addAllBtn  = document.getElementById('add-all');
    this.grupos     = document.querySelectorAll('.js-group-selector');
    this.qtd        = this.modal.querySelector('[name=qtd]');
    this.saveBtn    = this.modal.querySelector('.js-save');
    this.id         = 0;
    this.defaultQtd = 0;
    var _this       = this;


    $(this.modal).on('hide.bs.modal', function(e) {
      $(_this.insumo).select2('destroy');
    });

    $(this.modal).on('ifToggled', '.js-item', function(event) {
      _this.addAllBtn.dataset.qtd = event.currentTarget.dataset.qtd;
    });

    $body.on('click', '.js-reapropriar', this.reapropriar.bind(this));
    this.addAllBtn.addEventListener('click', this.addAll.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
  }

  Reapropriar.prototype.reapropriar = function(event) {
    var button = event.currentTarget;
    this.qtd.value = '';
    this.id = button.dataset.itemId;
    var self = this;

    var insumo = this.modal.querySelector('#grupos_de_orcamento_insumo_id');
    if(insumo) {
      insumo.value = button.dataset.insumoId;
    }

    getView(this.id, 'reapropriacao', this.modal);
  }

  Reapropriar.prototype.addAll = function(event) {
    if(!this.addAllBtn.dataset.qtd) {
      return false;
    }

    this.qtd.value = floatToMoney(parseFloat(this.addAllBtn.dataset.qtd), '');
  };

  Reapropriar.prototype.save = function() {
    if (!this.valid()) {
      return true;
    }

    var options = {
      title: 'Reapropriar insumo?',
      text: 'Ao confirmar não será possível voltar atrás',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Salvar',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    };

    swal(options, this.sendData.bind(this));
  };

  Reapropriar.prototype.sendData = function() {
    var _this = this;

    var item = this.getSelectedItem();

    var data = {
      _token: token,
      qtd: this.qtd.value,
    };

    data.item_id = item.value;

    data = getInputs(this.grupos, data);

    $.post('/contratos/reapropriar-item/' + this.id, data)
      .done(function(response) {
        swal({
          title: 'Sucesso!',
          text: 'Reapropriação salva com sucesso',
          type: 'success',
        }, function() {
          $(_this.modal).modal('hide');
          location.reload();
        });
      })
      .fail(function(response) {
        if (response.status === 422) {
          var errorList = new ErrorList(response.responseJSON);

          swal({
            title: '',
            text: errorList.toHTML(),
            type: 'warning',
            customClass: 'custom-alert',
            html: true
          });

          return true
        }

        swal('Ops!', 'Ocorreu um erro ao reapropriar insumo.', 'error');
      });
  };

  Reapropriar.prototype.getSelectedItem = function() {
    return this.modal.querySelector('.js-item:checked');
  };

  Reapropriar.prototype.valid = function() {
    var qtd = moneyToFloat(this.qtd.value);

    if (!this.getSelectedItem()) {
      swal('', 'Selecione o item a ser reapropriado', 'warning');
      return false;
    }

    if (qtd > parseFloat(this.addAllBtn.dataset.qtd)) {
      swal('', 'Neste item o máximo de reapropriação é ' + this.addAllBtn.dataset.qtd, 'warning');
      return false;
    }

    if (!this.qtd.value.length || moneyToFloat(this.qtd.value) === 0) {
      swal('', 'É necessário especificar a quantidade para reapropriar', 'warning');
      return false;
    }

    var filled = Array
      .from(this.grupos)
      .map(_.property('value'))
      .filter(Boolean)
      .length;

    if (filled !== this.grupos.length) {
      swal('', 'Selecione todos os grupos para reapropriação', 'warning');
      return false;
    }

    return true;
  };

  Reapropriar.init = function() {
    return new Reapropriar;
  };

  return Reapropriar;
}());

var Reajuste = (function() {
  function Reajuste() {
    this.modal = document.getElementById('modal-reajuste');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.anexo = this.modal.querySelector('[name=anexo]');
    this.id = 0;

    $body.on('click', '.js-reajuste', this.reajustar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
  }

  Reajuste.prototype.reajustar = function(event) {
    event.preventDefault();
    var self = this;
    var button = event.currentTarget;
    this.id = button.dataset.itemId;

    getView(this.id, 'reajuste', this.modal)
      .done(function() {
        self.inputs = self.modal.querySelectorAll('.js-input');

        self.valor = self.modal.querySelector('.js-valor');

        self.anexo = self.modal.querySelector('[name=anexo]');

        self.adicionais = _.filter(
          self.inputs,
          _.method('classList.contains', 'js-adicional')
        );

        var inputs = $(self.modal).find('.js-adicional');

        inputs.on('blur', self.adjustTotal.bind(this));
        inputs.on('change', self.adjustTotal.bind(this));
        inputs.on('keyup keypress keydown', self.adjustTotal.bind(this));
      });
  };

  Reajuste.prototype.adjustTotal = function(event) {
    var input = event.currentTarget;
    var valueContainer = $(input).parents('tr').find('td:last').get(0);

    valueContainer.innerText = floatToMoney(
      (input.value ? moneyToFloat(input.value) : 0) + parseFloat(valueContainer.dataset.itemQtd),
      ''
    );
  };

  Reajuste.prototype.save = function(event) {
    event.preventDefault();

    if (!this.valid()) {
      return false;
    }

    swal({
        title: 'Enviar reajuste para aprovação?',
        text: 'Ao confirmar não será possível voltar atrás',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#7ED32C'
      },
      this.sendData.bind(this)
    );
  };

  Reajuste.prototype.valid = function() {
    var adicionaisChanged = _.some(this.adicionais, function(input) {
      return input.value.length && moneyToFloat(input.value) > 0;
    });

    var valorChanged = parseFloat(this.valor.dataset.oldValue) !== moneyToFloat(this.valor.value);

    if (!adicionaisChanged && !valorChanged) {
      swal('', 'Não foram encontratas modificações no contrato', 'warning');

      return false;
    }

    return true;
  };

  Reajuste.prototype.sendData = function() {
    var _this = this;

    var data = {
      _token: token,
      valor_unitario: this.valor.value
    };


    data = _.reduce(this.inputs, function(data, input) {
      if (
        input.classList.contains('js-adicional') &&
        (input.value &&
          moneyToFloat(input.value) > 0)
      ) {
        data[input.name] = input.value;
      }

      return data;
    }, data);

    var formData = new FormData();

    $.map(data, function(value, index) {
      formData.append(index, value);
    });

    formData.append('anexo', $('input[name="anexo"]')[0].files[0]);

    $.ajax({
      type: 'POST',
      url: '/contratos/reajustar/' + this.id,
      data: formData,
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false
    }).done(function(response) {
        swal({
          title: 'Sucesso!',
          text: 'Reajuste enviado para aprovação',
          type: 'success',
        }, function() {
          $(_this.modal).modal('hide');
          location.reload();
        });
      })
      .fail(function(response) {
        if (response.status === 422) {
          var errorList = new ErrorList(response.responseJSON);

          swal({
            title: '',
            text: errorList.toHTML(),
            type: 'warning',
            customClass: 'custom-alert',
            html: true
          });

          return true
        }

        swal('Ops!', 'Ocorreu um erro ao criar reajuste.', 'error');
      });
  };

  Reajuste.init = function() {
    return new Reajuste;
  };

  return Reajuste;
}());

var Distrato = (function() {
  function Distrato() {
    this.modal = document.getElementById('modal-distrato');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.inputs = null;
    this.id = 0
    this.defaultQtd = 0;

    $body.on('click', '.js-distrato', this.distratar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
    $(this.modal).on('click', '.js-zerar', this.zerar.bind(this));
    $(this.modal).on('blur', '.js-input', this.validateInputs.bind(this));
  }

  Distrato.init = function() {
    return new Distrato;
  }

  Distrato.prototype.distratar = function(event) {
    event.preventDefault();
    var self = this;
    var button = event.currentTarget;
    this.id = button.dataset.itemId;

    getView(this.id, 'distrato', this.modal)
      .done(function() {
        self.inputs = self.modal.querySelectorAll('.js-input');
        var handler = function(event) {
          var input = event.currentTarget;

          if(moneyToFloat(input.value) > parseFloat(input.dataset.qtd)) {
            swal('', 'Você não pode distratar mais do que valor total', 'warning');

            input.value = floatToMoney(parseFloat(input.dataset.qtd), '');
            $(input).trigger('change');
            return false;
          }

          var valueContainer = $(input).closest('tr').find('td:last').get(0);

          valueContainer.innerText = floatToMoney(
            parseFloat(input.dataset.qtd) - (input.value ? moneyToFloat(input.value) : 0),
            ''
          );
        };

        $(self.inputs).on('blur', handler);
        $(self.inputs).on('change', handler);
        $(self.inputs).on('keyup keypress keydown input', handler);
      });
  };

  Distrato.prototype.validateInputs = function(event) {
    var input = event.currentTarget;
    var value = moneyToFloat(input.value, '');
    var oldValue = parseFloat(input.dataset.oldValue);

    if (value > oldValue) {
      input.value = floatToMoney(oldValue, '');
      input.focus();
      swal('Valor inválido!', 'O novo valor só pode ser menor do que o atual', 'warning');
    }
  };

  Distrato.prototype.zerar = function(event) {
    event.preventDefault();
    var input = $(event.currentTarget).parents('.input-group').find('input').get(0);

    input.value = floatToMoney(parseFloat(input.dataset.qtd), '');
    $(input).trigger('change');
  };

  Distrato.prototype.save = function(event) {
    event.preventDefault();

    if (!this.valid()) {
      return false;
    }

    swal({
      title: 'Enviar distrato para aprovação?',
      text: 'Ao confirmar não será possível voltar atrás',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Salvar',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, this.sendData.bind(this));

  };

  Distrato.prototype.valid = function() {
    var hasChanged = _.some(this.inputs, function(input) {
      return moneyToFloat(input.value) > 0;
    });

    var hasInvalid = _.some(this.inputs, function(input) {
      return moneyToFloat(input.value) > parseFloat(input.dataset.qtd);
    });

    if (!hasChanged) {
      swal('', 'Nenhuma modificação encontrada', 'warning');

      return false;
    }

    if(hasInvalid) {
      swal('', 'Formulário contem distratos inválidos', 'warning');

      return false;
    }

    return true;
  };

  Distrato.prototype.sendData = function() {
    var _this = this;
    var data = {
      _token: token,
    };

    var inputs = _.filter(this.inputs, function(input) {
      return input.value && moneyToFloat(input.value);
    });

    data = getInputs(inputs, data);

    $.post('/contratos/distratar/' + this.id, data)
      .done(function(response) {
        swal({
          title: 'Sucesso!',
          text: 'Distrato enviado para aprovação',
          type: 'success',
        }, function() {
          $(_this.modal).modal('hide');
          location.reload();
        });
      })
      .fail(function(response) {
        if (response.status === 422) {
          var errorList = new ErrorList(response.responseJSON);

          swal({
            title: '',
            text: errorList.toHTML(),
            type: 'warning',
            customClass: 'custom-alert',
            html: true
          });

          return true
        }

        swal('Ops!', 'Ocorreu um erro ao realizar distrato.', 'error');
      });
  };

  return Distrato;
}());

var Editar = (function() {
  function Editar() {
    this.modal = document.getElementById('modal-editar');
    this.qtd = this.modal.querySelector('[name=qtd]');
    this.valor = this.modal.querySelector('[name=valor]');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.valorDefault = 0;
    this.qtdDefault = 0;
    this.id = 0;

    $body.on('click', '.js-editar', this.editar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
  }

  Editar.prototype.editar = function(event) {
    event.preventDefault();
    var button = event.currentTarget;

    this.id = button.dataset.itemId;
    this.qtdDefault = parseFloat(button.dataset.itemQtd);
    this.valorDefault = parseFloat(button.dataset.itemValor);
    this.qtd.value = floatToMoney(this.qtdDefault, '');
    this.valor.value = floatToMoney(this.valorDefault, '');

    this.valor.dispatchEvent(new Event('input'));

    $(this.modal).modal('show');
  };

  Editar.prototype.save = function(event) {
    event.preventDefault();

    if (!this.valid()) {
      return false;
    }

    swal({
        title: 'Enviar aditivo para aprovação?',
        text: 'Ao confirmar não será possível voltar atrás',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#7ED32C'
      },
      this.sendData.bind(this)
    );
  };

  Editar.prototype.valid = function() {
    if (!this.valor.value.length) {
      swal('', 'É necessário enviar um valor', 'warning');
      return false;
    }

    if (moneyToFloat(this.qtd.value) === 0) {
      swal('', 'A nova quantidade não pode ser zero', 'warning');
      return false;
    }

    if (moneyToFloat(this.valor.value) === 0) {
      swal('', 'O novo valor não pode ser zero', 'warning');
      return false;
    }

    if (moneyToFloat(this.valor.value) === this.valorDefault &&
      moneyToFloat(this.qtd.value) === this.qtdDefault) {
      swal('', 'Você não fez nenhuma alteração no aditivo', 'warning');
      return false;
    }

    return true;
  };

  Editar.prototype.sendData = function() {
    var _this = this;
    var data = {
      _token: token,
      qtd: this.qtd.value,
      valor: this.valor.value
    };

    if (!this.valid()) {
      return false;
    }

    $.post('/contratos/editar-item/' + this.id, data)
      .done(function(response) {
        swal({
          title: 'Sucesso!',
          text: 'Aditivo enviado para aprovação',
          type: 'success',
        }, function() {
          $(_this.modal).modal('hide');
          location.reload();
        });
      })
      .fail(function(response) {
        if (response.status === 422) {
          var errorList = new ErrorList(response.responseJSON);

          swal({
            title: '',
            text: errorList.toHTML(),
            type: 'warning',
            customClass: 'custom-alert',
            html: true
          });

          return true
        }

        swal('Ops!', 'Ocorreu um erro ao editar o aditivo.', 'error');
      });
  };

  Editar.init = function() {
    return new Editar;
  };

  return Editar;
}());
