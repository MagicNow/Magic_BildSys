$(function() {
  $body.on('draw.dt', function() {
    $("input").iCheck({
      checkboxClass: "icheckbox_square-green",
      radioClass: "iradio_square-green",
      increaseArea: "20%" // optional
    });
  });

  $body.on('ifToggled', '.js-active-insumo', function(event) {
    var checkbox = event.currentTarget;

    if(checkbox.disabled) {
      return false;
    }

    if(checkbox.classList.contains('is-loading')) {
      checkbox.classList.remove('is-loading');
      return true;
    } else {
      checkbox.classList.add('is-loading');
    }

    var action = checkbox.checked ? 'enable' : 'disable';
    var actionPhrase = checkbox.checked ? 'disponível' : 'indisponível';

    $.post('/admin/insumos/' + checkbox.value + '/' + action)
      .done(function(insumo) {
        checkbox.classList.remove('is-loading');
      })
      .fail(function(response) {
        $(checkbox).iCheck(checkbox.checked ? 'uncheck' : 'check', false);
        var text = response.responseJSON ? response.responseJSON.error : false;
        var link = response.responseJSON ? response.responseJSON.link_option : false;
        var type = response.responseJSON ? response.responseJSON.type : false;

        var options = {
          title: '',
          text: text || 'Não foi possível alterar a disponibilidade do insumo',
          type: type || 'error'
        };

        if(link && link.length) {
          options = Object.assign(options, {
            showCancelButton: true,
            confirmButtonText: "OK!",
            cancelButtonText: "Gerenciar Grupos de Insumo",
            closeOnConfirm: true,
            closeOnCancel: false
          })
        }

        swal(options, function(isConfirm) {
          if(!isConfirm && link && link.length) {
            location.href = link;
          }
        });
      });
  });
});
