$(function() {
  // Somente executar na página de informar valores
  if(location.href.match(/[0-9]+\/informar-valor/gi)) {
    QcInformarValoresForm.init();
  }
});

var QcInformarValoresForm = {
  init: function() {
    var save         = document.getElementById('save');
    var rows         = document.querySelectorAll('.js-calc-row');
    var form         = document.getElementById('informar-valores-form');
    var reject       = document.getElementById('reject');
    var motivoSelect = document.getElementById('desistencia_motivo_id');
    var percents     = document.getElementsByClassName('js-percent');

    motivoSelect.classList.remove('hidden');

    var motivoSelectHtml = motivoSelect.outerHTML;

    motivoSelect.remove();

    _.each(rows, function(row) {
      var price = row.querySelector('.js-calc-price');
      price.addEventListener('input', function(event) {
        var price = event.currentTarget;
        var amount = row.querySelector('.js-calc-amount');
        var result = row.querySelector('.js-calc-result');

        if(!price.value.length) {
          result.innerText = 'R$ 0,00';

          return true;
        }

        result.innerText = floatToMoney(
          parseFloat(moneyToFloat(amount.innerText), 10) * moneyToFloat(price.value)
        );
      });

      price.dispatchEvent(new Event('input'));
    });

    reject.addEventListener('click', function(event) {
      event.preventDefault();
      swal({
        title: "Rejeitar proposta?",
        text: '<label for="desistencia_motivo_id">Escolha um motivo</label>' +
        motivoSelect.outerHTML,
        html: true,
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Justificativa",
        showLoaderOnConfirm: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Rejeitar',
        confirmButtonColor: '#DD6B55'
      },
        function (justificativa_texto) {
          if (justificativa_texto === false) return false;

          if (!justificativa_texto.length) {
            swal.showInputError("Escreva uma justificativa!");
            return false
          }
          var motivo = $('#desistencia_motivo_id');

          if (!motivo.val().length) {
            swal.showInputError("Escolha um motivo!");
            return false
          }
          $(form).append($(
            '<input type="hidden" class="js-additional-input" name="desistencia_texto" value="' + justificativa_texto + '" />' +
            '<input type="hidden" class="js-additional-input" name="desistencia_motivo_id" value="' + motivo.val()  + '" />' +
            '<input type="hidden" class="js-additional-input" name="reject" value="1" />'
          ));

          form.submit();
        });
    });

    save.addEventListener('click', function(event) {
      event.preventDefault();

      var percentualSum = _(percents)
          .map(_.property('value'))
          .map(moneyToFloat)
          .reject(_.isNaN)
          .sum();

      if(percentualSum !== 100) {
        swal({
          title: '',
          text: 'As porcentagens não somam 100%',
          type: 'error'
        }, function() {
          _.first(percents).focus();
        });

        return false;
      }

      swal({
        title: 'Salvar preços?',
        text: 'Ao confirmar não será possível voltar atrás',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#7ED32C'
      }, function () {
        form.submit();
      });
    });
  }
}
