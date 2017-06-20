function startLoading() {
  if (!$('.loader').length) {
    $('body').append('<div class="loader"></div>');
  }
}

function showHideInfoExtra(qual) {
  var icone_expandir = $('#icone-expandir' + qual);
  var dados_extras = $('#dados-extras' + qual);

  if (icone_expandir.hasClass('fa-caret-right')) {
    dados_extras.show();
    icone_expandir.parent().attr('title', 'Fechar');
    icone_expandir.removeClass('fa-caret-right');
    icone_expandir.addClass('fa-caret-down');
  } else { //aberto
    dados_extras.hide();
    icone_expandir.parent().attr('title', 'Expandir');
    icone_expandir.removeClass('fa-caret-down');
    icone_expandir.addClass('fa-caret-right');
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

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

  $('form').submit(function(event) {
    $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
  });

  $('.money').maskMoney({
    allowNegative: true,
    thousands: '.',
    decimal: ','
  });
  $('.money_3').mask('0.000.000.000.000,000', {
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
      if (content.length) {
        return content.html();
      }

      return this.dataset.content;
    }
  };

  $('[data-toggle="popover"]').popover(popoverOptions);

  $document.on('draw.dt', function() {
    $('[data-toggle="popover"]').popover(popoverOptions);
    if (isMobile()) {
      $('.dataTable td > form .btn-group')
        .removeClass('btn-group')
        .find('.btn')
        .removeClass('btn-xs')
        .addClass('btn-sm');
    }
  });

  $('.htmleditor').summernote({
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['table', ['table']],
      ['insert', ['link', 'hr', 'picture']],
      ['view', ['fullscreen', 'codeview']],
      ['help', ['help']]
    ],
    lang: 'pt-BR',
    cleaner: {
      notTime: 2400, // Time to display Notifications.
      action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
      newline: '<br>', // Summernote's default is to use '<p><br></p>'
      notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
      // icon:'<i class="note-icon">[Your Button]</i>'
      keepHtml: false, //Remove all Html formats
      keepClasses: false, //Remove Classes
      badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], //Remove full tags with contents
      badAttributes: ['style', 'start'] //Remove attributes from remaining tags
    }
  });

  $(".selecionavel").on('mouseup', function() {
    var sel, range;
    var el = $(this)[0];
    if (window.getSelection && document.createRange) { //Browser compatibility
      sel = window.getSelection();
      if (sel.toString() == '') { //no text selection
        window.setTimeout(function() {
          range = document.createRange(); //range object
          range.selectNodeContents(el); //sets Range
          sel.removeAllRanges(); //remove all ranges from selection
          sel.addRange(range); //add Range to a Selection.
        }, 1);
      }
    } else if (document.selection) { //older ie
      sel = document.selection.createRange();
      if (sel.text == '') { //no text selection
        range = document.body.createTextRange(); //Creates TextRange object
        range.moveToElementText(el); //sets Range
        range.select(); //make selection.
      }
    }
  });

  $document.on('click', function(e) {
    $('[data-toggle="popover"],[data-original-title]').each(function() {
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        (($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
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
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
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

function makeSelect2(selector, url) {

  function formatResultSelection(obj) {
    if (obj.nome) {
      return obj.nome;
    }
    return obj.text;
  }

  function formatResult(obj) {
    if (obj.loading) return obj.text;

    var markup = "<div class='select2-result-obj clearfix'>" +
      "   <div class='select2-result-obj__meta'>" +
      "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
      "   </div>" +
      "</div>";

    return markup;
  }

  var element = $(selector);
  var options = {
    allowClear: true,
    placeholder: "-",
    language: "pt-BR",
    theme: 'bootstrap',
    ajax: {
      dataType: 'json',
      delay: 250,

      data: function(params) {
        return {
          q: params.term,
          page: params.page
        };
      },

      processResults: function(result, params) {
        params.page = params.page || 1;

        return {
          results: result.data,
          pagination: {
            more: (params.page * result.per_page) < result.total
          }
        };
      },
      cache: true
    },
    escapeMarkup: function(markup) {
      return markup;
    },
    minimumInputLength: 1,
    templateResult: formatResult,
    templateSelection: formatResultSelection
  };

  if (typeof url === 'string') {
    options.ajax.url = url;
    element.select2(options);

    return element;
  }

  element.select2(_.merge(options, url));

  return element;
}

/* Brazilian initialisation for the jQuery UI date picker plugin. */
/* Written by Leonildo Costa Silva (leocsilva@gmail.com). */
jQuery(function($) {
  $.datepicker.regional['pt-BR'] = {
    closeText: 'Fechar',
    prevText: '&#x3c;Anterior',
    nextText: 'Pr&oacute;ximo&#x3e;',
    currentText: 'Hoje',
    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
      'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
    ],
    dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
    dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 0,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
});

