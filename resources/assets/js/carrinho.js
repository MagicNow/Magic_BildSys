$(function() {
  Carrinho.init();
});

var Carrinho = {
  init: function() {
    $('.js-aditivar').on('click', this.selecionarContrato.bind(this));
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

  iframeLoaded: function(event) {
    var iframe = event.currentTarget;
    $(iframe).contents().on('click', '.js-indicar', this.indicarContrato.bind(this));
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

    $.colorbox({
      href: '/ordens-de-compra/carrinho/indicar-contrato?insumo=' + insumo,
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

