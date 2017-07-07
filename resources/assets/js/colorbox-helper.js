function colorbox(options) {
  options = Object.assign({
    iframe: true,
    width: '90%',
    height: '90%',
  }, options);

  return new Promise(function(resolve, reject) {
    options.onComplete = function() {
      var $iframe = $('#colorbox iframe');
      $iframe.on('load', function(event) {
        resolve({
          window: event.currentTarget.contentWindow,
          element: event.currentTarget
        })
      });
    };
    $.colorbox(options);
  });
}

