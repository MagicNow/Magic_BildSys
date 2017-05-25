$(function() {
  Reajuste.init();
  Distrato.init();
  Reapropriar.init();
});

var Reapropriar = (function() {
  function Reapropriar() {
    this.modal = document.getElementById('modal-reapropriar');
    $body.on('click', '.js-reapropriar', this.reapropriar.bind(this));
  }

  Reapropriar.prototype.reapropriar = function(event) {
    console.log('zica', this);

    $(this.modal).modal('show');
  }

  Reapropriar.init = function() {
    return new Reapropriar;
  }

  return Reapropriar;
}());

var Reajuste = (function() {
  function Reajuste() {
    this.modal   = document.getElementById('modal-reajuste');
    this.total   = this.modal.querySelector('[name=total]');
    this.qtd     = this.modal.querySelector('[name=qtd]');
    this.valor   = this.modal.querySelector('[name=valor]');
    this.saveBtn = this.modal.querySelector('.js-save');
    this.totalDefault = 0;
    this.id = 0;

    $body.on('click', '.js-reajuste', this.reajustar.bind(this));
    this.saveBtn.addEventListener('click', this.save.bind(this));
    this.qtd.addEventListener('input', this.adjustTotal.bind(this));
  }

  Reajuste.prototype.reajustar = function(event) {
    event.preventDefault();
    var button        = event.currentTarget;
    this.id           = button.dataset.itemId;
    this.valor.value  = button.dataset.itemValor;
    this.totalDefault = parseFloat(button.dataset.itemQtd);
    this.total.value  = floatToMoney(parseFloat(button.dataset.itemQtd), '');

    this.valor.dispatchEvent(new Event('input'));

    $(this.modal).modal('show');
  };

  Reajuste.prototype.adjustTotal = function(event) {
    var qtd = moneyToFloat(this.qtd.value || '0');

    this.total.value = floatToMoney(this.totalDefault + qtd, '');
  };

  Reajuste.prototype.save = function(event) {
    event.preventDefault();
    swal(
      {
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

  Reajuste.prototype.sendData = function() {
    console.log('zica');

    $.post('/contratos/reajustar-item/' + this.id)
      .done(function(response) {
        swal.close();
      })
      .fail(function(response) {
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
    this.id = this.modal.querySelector('[name="contrato_item_id"]');
    this.qtd = this.modal.querySelector('[name="qtd"]');
    this.saveBtn = this.modal.querySelector('.js-save');

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

    this.id.value = button.dataset.itemId;
    this.qtd.value = button.dataset.itemQtd;

    this.qtd.dispatchEvent(new Event('input'));

    $(this.modal).modal('show');
  };

  Distrato.prototype.zerar = function(event) {
    event.preventDefault();
    distratoInputQtd.value = 0;
    distratoInputQtd.dispatchEvent(new Event('input'));
  };

  Distrato.prototype.save = function(event) {
    event.preventDefault();
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
    }, function() {});
  };

  return Distrato;
}());

