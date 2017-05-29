function startLoading() {
  if (!$('.loader').length) {
    $('body').append('<div class="loader"></div>');
  }
}

function stopLoading() {
  if ($('.loader').length) {
    $('.loader').fadeToggle(function() {
      $(this).remove();
    });
  }
}

var mascara = function(val) {
  return val.replace(/\D/g, '').length === 14 ? '(00) 00000-0000' : '(00) 0000-00009';
},
  options = {
    onKeyPress: function(val, e, field, options) {
      field.mask(mascara.apply({}, arguments), options);
    }
  };

$(function() {
  $(".select2").select2({
    theme: 'bootstrap',
    placeholder: "-",
    language: "pt-BR",
    allowClear: true
  });

  $('input:not(.btn > input)').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '20%' // optional
  });

  $('.colorbox').colorbox({
    transition: "fade",
    width: "95%",
    height: "95%"
  });
  $('form').submit(function(evento) {
    $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
  });

  $('.money').mask('0.000.000.000.000,00', {
    reverse: true
  });
  $('.decimal').mask('00,00');
  $('.percent').mask('#00,00', {
    reverse: true
  });

  $('.datepicker').datepicker();

  $('.cnpj').mask('99.999.999/9999-99');
  $('.cep').mask('00000-000');
  $('.telefone').mask(mascara, options);

  var popoverOptions = {
    html: true,
    content: function() {
      var content = $(this.dataset.externalContent);
      if(content.length) {
        return content.html();
      }

      return this.dataset.content;
    }
  };

  $('[data-toggle="popover"]').popover(popoverOptions);

  $document.on('draw.dt', function() {
    $('[data-toggle="popover"]').popover(popoverOptions);
  });

  $document.on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
        }
    });
  });

});

/**
 * Transforms money string (9.999,9) to float
 *
 * @param {String} money
 *
 * @return {Number}
 */
function moneyToFloat(money) {
  return parseFloat(money.replace(/\./g, '').replace(',', '.'), 10);
}

/**
 * Transforms money string (9.999,9) to float
 *
 * @param {Number} number
 *
 * @return {String}
 */
function floatToMoney(number, prefix) {
  prefix = prefix == undefined ? 'R$ ' : '';
  return prefix + number.toLocaleString('pt-BR', {
    minimumFractionDigits: 2
  });
}

/**
 * Remove tudo que não for número
 *
 * @param {String} string
 *
 * @return {Number}
 */
function removeNaoNumero(str) {
  return parseInt(str.replace(/[\D]+/g, ''));
}

/**
 * Tranforma o parâmetro em formato de moeda Real
 *
 * @param {Integer} integer
 *
 * @return {String}
 */
function formatarReal(int) {
  var tmp = int + '';
  tmp = tmp.replace(/\D/g, '');
  tmp = tmp.replace(/(\d{1,2})$/, ',$1');
  tmp = tmp.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  tmp = tmp != '' ? tmp : '';

  return tmp;
}

var oTable = null;

window.$body = $(document.body);
window.$document = $(document);

/* Brazilian initialisation for the jQuery UI date picker plugin. */
/* Written by Leonildo Costa Silva (leocsilva@gmail.com). */
jQuery(function($){
  $.datepicker.regional['pt-BR'] = {
    closeText: 'Fechar',
    prevText: '&#x3c;Anterior',
    nextText: 'Pr&oacute;ximo&#x3e;',
    currentText: 'Hoje',
    monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
      'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
      'Jul','Ago','Set','Out','Nov','Dez'],
    dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 0,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
});
