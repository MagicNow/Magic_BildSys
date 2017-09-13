$(function() {
  Carrinho.init();
});

var Carrinho = {
  init: function() {
    $('.js-aditivar').on('click', this.selecionarContrato.bind(this));
    $('.js-remove-contrato').on('click', this.removerContrato.bind(this));
  },

  alterarItem: function(id, column, value) {
    return $.post('/ordens-de-compra/altera-item/' + id, {
      coluna: column,
      conteudo: value
    })
      .fail(function(response) {
        var errorList = new ErrorList(response.responseJSON);
        swal({
          title: '',
          text: errorList.toHTML(),
          type: 'warning',
          customClass: 'custom-alert',
          html: true
        });

        return true
      });
  },

  removerContrato: function(event) {
    startLoading();
    var ajax = $.get('/ordens-de-compra/carrinho/remove-contrato', {
      item: event.currentTarget.dataset.item
    });

    ajax.always(stopLoading);
    ajax.done(function(response) {
      location.reload();
    });
    ajax.fail(function(xhr) {
      swal({
        title: 'Ops!',
        text: 'Ocorreu um erro ao remover indicação',
        type: 'error',
      });
    });
  },

  iframeLoaded: function(event) {
    var iframe = event.currentTarget;
    var iframeWindow = iframe.contentWindow;
    var $contents = $(iframe).contents();


    $contents
      .on('click', '.js-indicar', this.indicarContrato.bind(this));

    iframeWindow.LaravelDataTables.dataTableBuilder
      .on('draw', this.verifyIfTableIsEmpty.bind(this));
  },

  verifyIfTableIsEmpty: function(event) {
    var iframeWindow = event.currentTarget.ownerDocument.defaultView;
    var table = iframeWindow.LaravelDataTables.dataTableBuilder;

    if(!table.data().length) {
      swal({
        title: '',
        text: 'Não há contratos disponíveis para serem aditivados por esse insumo.',
        type: 'info',
      }, function() {
        $.colorbox.close();
      })
    }
  },

  indicarContrato: function(event) {
    var button = event.currentTarget;
    var iframeWindow = button.ownerDocument.defaultView;
    iframeWindow.startLoading();

    this.alterarItem(this.item, 'sugestao_contrato_id', button.dataset.contrato)
      .always(iframeWindow.stopLoading)
      .done(function() {
        $.colorbox.close();
        location.reload();
      });
  },

  selecionarContrato: function(event) {
    var self   = this;
    var button = event.currentTarget;
    var insumo = button.dataset.insumo;
    this.item = button.dataset.item;
    var obra = button.dataset.obra;

    $.colorbox({
      href: '/ordens-de-compra/carrinho/indicar-contrato?insumo='+ insumo+'&obra_id='+obra,
      iframe: true,
      width: '90%',
      height: '90%',
      onComplete: function() {
        var $iframe = $('#colorbox iframe');
        $iframe.on('load', self.iframeLoaded.bind(self));
      }
    });
  }
};

