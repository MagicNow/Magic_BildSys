$(function() {
  $('[name=days]').on('change', function(event) {
    $('[name=days]').each(function(n, input) {
      $(input).parent().removeClass('active');
    });

    if(event.currentTarget.checked) {
      $(event.currentTarget).parent().addClass('active');
      $('[name=data_start],[name=data_end]').val('');
    }

  });
  $('[name=data_start],[name=data_end]').on('change', function(e) {
    if(e.currentTarget.value) {
      $('[name=days]').prop('checked', false).trigger('change');
    }
  });
});
