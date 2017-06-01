$(function() {
  $.fn.modal.Constructor.prototype.enforceFocus = $.noop;
  Reajuste.init();
  Distrato.init();
  Reapropriar.init();

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

        button[0].innerHTML = !visible
          ? '<i class="fa fa-money"></i> Exibir Impostos'
          : '<i class="fa fa-money"></i> Ocultar Impostos';
      },
      text: '<i class="fa fa-money"></i> Exibir Impostos'
    });
  })

});

var token = document.currentScript.dataset.token;

var ErrorList = (function() {
  function ErrorList(errors) {
    this.errors = errors;
  }

  ErrorList.prototype.parse = _.flow([_.values, _.flatten]);

  ErrorList.prototype.toHTML = function() {

    var errors = this.parse(this.errors)
      .map(this.makeItem);

    return _(['<ul class="list-group">', errors, '</ul>']).flatten().join('');
  }

  ErrorList.prototype.makeItem = function(value) {
    var template = _.template(
      '<li class="list-group-item"><%= value %></li>'
    );

    return template({
      value: value
    });
  };

  return ErrorList;
}());


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
      _this.addAllBtn.dataset.qtd = event.currentTarget.dataset.qtdMax;
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

    startLoading();
    this.getView(this.id)
      .always(stopLoading)
      .done(function(html) {
        $(self.modal).find('#select-item').html(html);
        $(self.modal).modal('show');
        $(self.modal).find('input[type="radio"]').iCheck({
          checkboxClass: 'icheckbox_square-green',
          radioClass: 'iradio_square-green',
          increaseArea: '20%' // optional
        });
      });
  }

  Reapropriar.prototype.addAll = function(event) {
    this.qtd.value = floatToMoney(this.addAllBtn.dataset.qtd, '');
  };

  Reapropriar.prototype.getView = function(id) {
    return $.get('/contratos/reapropriar-item/' + id);
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
    var data = {
      _token: token,
      qtd: this.qtd.value,
    };

    var item = this.getSelectedItem();

    data[item.dataset.column] = item.value;

    data = _.reduce(this.grupos, function(data, grupo) {
      data[grupo.name] = grupo.value;
      return data;
    }, data);

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

        swal('Ops!', 'Ocorreu um erro ao realizar distrato.', 'error');
      });
  };

  Reapropriar.prototype.getSelectedItem = function() {
    return this.modal.querySelector('.js-item:checked');
  };

  Reapropriar.prototype.valid = function() {
    var qtd = moneyToFloat(this.qtd.value);

    if(!this.getSelectedItem()) {
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
    this.total = this.modal.querySelector('[name=total]');
    this.qtd = this.modal.querySelector('[name=qtd]');
    this.valor = this.modal.querySelector('[name=valor]');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.totalDefault = 0;
    this.id = 0;

    $body.on('click', '.js-reajuste', this.reajustar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
    this.qtd.addEventListener('input', this.adjustTotal.bind(this));
  }

  Reajuste.prototype.reajustar = function(event) {
    event.preventDefault();
    var button = event.currentTarget;
    this.id = button.dataset.itemId;
    this.qtd.value = '';
    this.valor.value = button.dataset.itemValor;
    this.totalDefault = parseFloat(button.dataset.itemQtd);
    this.valorDefault = parseFloat(button.dataset.itemValor);
    this.total.value = floatToMoney(parseFloat(button.dataset.itemQtd), '');

    this.valor.dispatchEvent(new Event('input'));

    $(this.modal).modal('show');
  };

  Reajuste.prototype.adjustTotal = function(event) {
    var qtd = moneyToFloat(this.qtd.value || '0');

    this.total.value = floatToMoney(this.totalDefault + qtd, '');
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
    if (!this.valor.value.length) {
      swal('', 'É necessário enviar um valor para reajuste', 'warning');
      return false;
    }

    if (moneyToFloat(this.valor.value) === 0) {
      swal('', 'O novo valor não pode ser zero', 'warning');
      return false;
    }

    if (moneyToFloat(this.valor.value) === this.valorDefault && !this.qtd.value) {
      swal('', 'Você não fez nenhuma alteração para reajuste', 'warning');
      return false;
    }

    return true;
  };

  Reajuste.prototype.sendData = function() {
    var _this = this;
    var data = {
      _token: token,
      qtd: this.qtd.value,
      valor: this.valor.value
    };

    $.post('/contratos/reajustar-item/' + this.id, data)
      .done(function(response) {
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
    this.zerarBtn = document.getElementById('zerar-saldo');
    this.qtd = this.modal.querySelector('[name="qtd"]');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.id = 0
    this.defaultQtd = 0;

    $body.on('click', '.js-distrato', this.distratar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
    this.zerarBtn.addEventListener('click', this.zerar.bind(this));
  }

  Distrato.init = function() {
    return new Distrato;
  }

  Distrato.prototype.distratar = function(event) {
    event.preventDefault();
    var button = event.currentTarget;

    this.id = button.dataset.itemId;
    this.qtd.value = button.dataset.itemQtd;
    this.defaultQtd = parseFloat(button.dataset.itemQtd);

    this.qtd.dispatchEvent(new Event('input'));

    $(this.modal).modal('show');
  };

  Distrato.prototype.zerar = function(event) {
    event.preventDefault();
    this.qtd.value = 0;
    this.qtd.dispatchEvent(new Event('input'));
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
    var qtd = moneyToFloat(this.qtd.value);

    if (qtd < this.defaultQtd) {
      return true;
    }

    if (qtd === this.defaultQtd) {
      swal('', 'A quantidade não foi alterada', 'warning');
    }

    if (qtd > this.defaultQtd) {
      swal('', 'A nova quantidade não pode ser maior que a atual', 'warning');
    }

    return false;
  };

  Distrato.prototype.sendData = function() {
    var _this = this;
    var data = {
      _token: token,
      qtd: this.qtd.value,
    };

    $.post('/contratos/distratar-item/' + this.id, data)
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

