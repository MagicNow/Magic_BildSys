$(function() {
  $('.js-sweetalert').click(function(event) {
    event.preventDefault();

    var button = event.currentTarget;

    if(!button.dataset.message) {
      throw new Error('Sweetalert helper without message');
      return false;
    }

    swal({
      title: button.dataset.title || button.innerText,
      text: button.dataset.message
    });
  });
});
