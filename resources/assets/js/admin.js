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

    // $('input').iCheck({
    //     checkboxClass: 'icheckbox_square-green',
    //     radioClass: 'iradio_square-green',
    //     increaseArea: '20%' // optional
    // });
    $('.colorbox').colorbox({transition: "fade", width: "95%", height: "95%"});
    $('form').submit(function (evento) {
        $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    });

    $('.money').mask('0.000.000.000.000,00', {reverse: true});
    $('.decimal').mask('00,00');
});

var oTable = null;