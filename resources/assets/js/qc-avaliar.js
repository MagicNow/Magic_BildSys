$(function() {

  if (!location.href.match(/quadro-de-concorrencia\/[0-9]+\/avaliar/gi)) {
    return false;
  }

  var modal = $('#equalizacao-tecnica');

  var $changeRoundRadio = $('.js-change-round');

  $changeRoundRadio.on('ifChecked', function(event) {
    var radio = event.currentTarget;
    startLoading();
    location.href = [location.pathname, '?rodada=', radio.value].join('');
  });

  $('[data-qcfornecedor]').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    startLoading();

    $.get(urlEqualizacao + this.dataset.qcfornecedor)
      .done(function(html) {
        modal.find('.modal-body').html(html);
        modal.modal('show');
      })
      .fail(function(x) {
        swal('Não encontrado', 'Quadro de Concorrência não econtrado.', 'error');
      })
      .always(function() {
        stopLoading();
      });
  });

  var chartTotalFornecedor = document.getElementById('chart-total-fornecedor');
  var chartInsumoFornecedor = document.getElementById('chart-insumo-fornecedor');

  var labels = chartTotalFornecedor.dataset.labels.split('||');
  var values = chartTotalFornecedor.dataset.values.split('||');

  var __chartTotalFornecedor = new Chart(chartTotalFornecedor, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Valor Total na rodada selecionada',
        data: values,
        backgroundColor: _.times(labels.length, _.partial(generateFlatColor, undefined)),
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            return 'Valor total: ' + floatToMoney(tooltipItem.yLabel);
          }
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  });

  var selectInsumo = $('#insumo');
  var ofertas = JSON.parse(chartInsumoFornecedor.dataset.data);
  var ofertasDoInsumo = _.filter(ofertas, {
    'insumo_id': parseInt(selectInsumo.val(), 10)
  });

  var __chartInsumoFornecedor = new Chart(chartInsumoFornecedor, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Insumo na rodada selecionada',
        data: _.map(ofertasDoInsumo, _.property('valor_total')),
        backgroundColor: _.times(labels.length, _.partial(generateFlatColor, undefined)),
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            return 'Valor total do insumo: ' + floatToMoney(tooltipItem.yLabel);
          }
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            max: _.max(_.map(ofertasDoInsumo, _.property('valor_total'))) + 100
          }
        }]
      }
    }
  });

  selectInsumo.change(function() {
    var ofertasDoInsumo = _.filter(ofertas, {
      'insumo_id': parseInt(selectInsumo.val(), 10)
    });

    __chartInsumoFornecedor.data.datasets[0] = {
      label: 'Insumo na rodada selecionada',
      data: _.map(ofertasDoInsumo, _.property('valor_total')),
      backgroundColor: _.times(labels.length, _.partial(generateFlatColor, undefined)),
    };

    __chartInsumoFornecedor.options.scales.yAxes[0].ticks.max = _.max(_.map(ofertasDoInsumo, _.property('valor_total'))) + 100;

    __chartInsumoFornecedor.update();
  });

  var formFinalizar = document.getElementById('form-finalizar');

  var hasCheckedElement = _.flow([
    _.partialRight(_.map, _.property('checked')),
    _.partialRight(_.filter, Boolean),
    _.size,
    Boolean
  ]);

  $('#nova-rodada').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();

    var fornecedoresContainer = document.getElementById('fornecedores-container');

    var userCheckedOneFornecedor = hasCheckedElement(
      fornecedoresContainer.querySelectorAll('input[type="checkbox"]')
    );

    if (!userCheckedOneFornecedor) {
      swal({
        title: 'Próxima Rodada',
        text: 'Por favor, selecione pelo menos um fornecedor para permanecer na próxima rodada.',
        type: 'error',
      })

      return false;
    }

    swal({
      title: 'Gerar nova rodada?',
      text: 'Ao confirmar não será possível voltar atrás',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Gerar',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, function() {

      $(formFinalizar).append('<input type="hidden" name="gerar_nova_rodada" value="1">');
      formFinalizar.submit();
    });
  });

  $('#finalizar').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();

    var getInsumosNaoSelecionados = _.flow([
      _.partialRight(_.pickBy, _.partial(_.isEqual, false)),
      _.keys
    ]);

    var rows = document.getElementsByClassName('js-insumo-row');

    var insumos = _.reduce(rows, function(insumos, row) {
      var insumo = row.querySelector('td').innerText;
      var inputs = row.querySelectorAll('input[type="checkbox"]');

      insumos[insumo] = hasCheckedElement(inputs);

      return insumos;
    }, {});

    var notSelected = getInsumosNaoSelecionados(insumos);

    if (notSelected.length) {
      swal({
        title: 'Selecionar vencedor',
        customClass: 'custom-alert',
        text: '<p>Por favor, selecione o vencedor dos seguintes insumos:</p>' +
          '<ul class="list-group"><li class="list-group-item">' +
          notSelected.join('</li><li class="list-group-item">') +
          '</li></ul>',
        type: 'error',
        html: true,
      })

      return false;
    }

    swal({
      title: 'Finalizar quadro de concorrência?',
      text: 'Ao confirmar não será possível voltar atrás',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Finalizar',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#7ED32C'
    }, function() {
      formFinalizar.submit();
    });
  });

});

