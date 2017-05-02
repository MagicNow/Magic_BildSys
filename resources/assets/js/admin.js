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
    $('.cep').mask('00000-000');
    $('.telefone').mask(mascara, options);

});

function validaCnpj(qual) {
    if($('#numero'+qual).val()!=''){
        $.ajax({
            url: "/admin/valida-documento",
            data: {
                numero: $('#numero'+qual).val(),
                cpf: 1
            }
        }).fail(function(retorno) {
            if(retorno.responseJSON.erro){
                swal({
                    title: retorno.responseJSON.erro,
                    text: "",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Ok",
                    closeOnConfirm: false
                });
            }else {
                numero = $('#numero' + qual).val();
                resposta = !numero.length ? 'Nulo' : 'Inválido';

                swal({
                    title: 'Número ' + resposta,
                    text: "Este registro não será salvo enquanto o mesmo não for um número válido",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });

                $('#numero' + qual).val('');
                $('#numero' + qual).focus();
            }
        });
    }

}

var oTable = null;