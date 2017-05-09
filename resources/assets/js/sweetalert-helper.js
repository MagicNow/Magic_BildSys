$(function() {
  var buttons = document.getElementsByClassName('js-sweetalert');

  _.each(buttons, function(button) {
    button.addEventListener('click', function() {
      event.preventDefault();

      var button = event.currentTarget;

      if(!button.dataset.text) {
        console.error('Sweetalert helper without text', button)
        return false;
      }

      var options = Object.assign({
        title: button.innerText,
        type: 'info',
      }, button.dataset);

      swal(options);
    }, false);
  });
});
