function startLoading() {
  if (!$('.loader').length) {
    $('body').append('<div class="loader"></div>');
  }
}

function stopLoading() {
  if ($('.loader').length) {
    $('.loader').fadeToggle(function () {
      $(this).remove();
    });
  }
}

$(function () {
  $(".select2").select2({
    theme: 'bootstrap',

    placeholder: "-",
    language: "pt-BR",
  });

  $('input').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '20%' // optional
  });
  $('.colorbox').colorbox({transition: "fade", width: "95%", height: "95%"});
  $('form').submit(function (evento) {
    $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
  });

  $('.money').mask('0.000.000.000.000,00', {reverse: true});
  $('.decimal').mask('00,00');
  $('.cnpj').mask('99.999.999/9999-99');
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
function floatToMoney(number) {
  return 'R$ ' + number.toLocaleString('pt-BR', {minimumFractionDigits: 2});
}

var oTable = null;
