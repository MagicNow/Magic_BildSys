$(function() {
  var rows = document.querySelectorAll('.js-calc-row');

  _.each(rows, function(row) {
    var price = row.querySelector('.js-calc-price');
    price.addEventListener('input', function(event) {
      var price = event.currentTarget;
      var amount = row.querySelector('.js-calc-amount');
      var result = row.querySelector('.js-calc-result');

      console.log(price.value.length);

      if(!price.value.length) {
        result.innerText = 'R$ 0,00';

        return true;
      }

      result.innerText = floatToMoney(
        parseFloat(amount.innerText, 10) * moneyToFloat(price.value)
      );

    });
  });
});
