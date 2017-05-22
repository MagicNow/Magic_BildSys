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

var mascara = function (val) {
  return val.replace(/\D/g, '').length === 14 ? '(00) 00000-0000' : '(00) 0000-00009';
},
  options = {
    onKeyPress: function (val, e, field, options) {
      field.mask(mascara.apply({}, arguments), options);
    }
  };

$(function () {
  $(".select2").select2({
    theme: 'bootstrap',
    placeholder: "-",
    language: "pt-BR",
    allowClear: true
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
  $('.percent').mask('#00,00', {reverse: true});

  $('.cnpj').mask('99.999.999/9999-99');
  $('.cep').mask('00000-000');
  $('.telefone').mask(mascara, options);

  $('[data-toggle="popover"]').popover();

  $(document).on('draw.dt', function() {
    $('[data-toggle="popover"]').popover();
  });

  $('.htmleditor').summernote({
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['table', ['table']],
      ['insert', ['link', 'hr','picture']],
      ['view', ['fullscreen', 'codeview']],
      ['help', ['help']]
    ],
    lang: 'pt-BR',
    cleaner:{
      notTime:2400, // Time to display Notifications.
      action:'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
      newline:'<br>', // Summernote's default is to use '<p><br></p>'
      notStyle:'position:absolute;top:0;left:0;right:0', // Position of Notification
      // icon:'<i class="note-icon">[Your Button]</i>'
      keepHtml: false, //Remove all Html formats
      keepClasses: false, //Remove Classes
      badTags: ['style','script','applet','embed','noframes','noscript', 'html'], //Remove full tags with contents
      badAttributes: ['style','start'] //Remove attributes from remaining tags
    }
  });

  $(".selecionavel").on('mouseup', function() {
    var sel, range;
    var el = $(this)[0];
    if (window.getSelection && document.createRange) { //Browser compatibility
      sel = window.getSelection();
      if(sel.toString() == ''){ //no text selection
        window.setTimeout(function(){
          range = document.createRange(); //range object
          range.selectNodeContents(el); //sets Range
          sel.removeAllRanges(); //remove all ranges from selection
          sel.addRange(range);//add Range to a Selection.
        },1);
      }
    }else if (document.selection) { //older ie
      sel = document.selection.createRange();
      if(sel.text == ''){ //no text selection
        range = document.body.createTextRange();//Creates TextRange object
        range.moveToElementText(el);//sets Range
        range.select(); //make selection.
      }
    }
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
function floatToMoney(number) {
  return 'R$ ' + number.toLocaleString('pt-BR', {minimumFractionDigits: 2});
}

/**
 * Remove tudo que não for número
 *
 * @param {String} string
 *
 * @return {Number}
 */
function removeNaoNumero( str )
{
  return parseInt( str.replace(/[\D]+/g,'') );
}

/**
 * Tranforma o parâmetro em formato de moeda Real
 *
 * @param {Integer} integer
 *
 * @return {String}
 */
function formatarReal( int )
{
  var tmp = int+'';
  tmp=tmp.replace(/\D/g,'');
  tmp=tmp.replace(/(\d{1,2})$/, ',$1');
  tmp=tmp.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  tmp = tmp != '' ? tmp : '';

  return tmp;
}

var oTable = null;

