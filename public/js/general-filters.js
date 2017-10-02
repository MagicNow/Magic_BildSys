$(function() {
  var table = LaravelDataTables.dataTableBuilder;

  var queryString = _.template('<%= key %>=<%= value %>&');

  $body.on('change', '.js-filter', function(event) {
    if(event.currentTarget.disabled) {
      return true;
    }

    var inputs = document.getElementsByClassName('js-filter');
    inputs = _(inputs)
      .filter(function(input) {
        return ['radio', 'checkbox'].includes(input.type) ? input.checked : true;
      });


    var filters = inputs.reduce(function(filters, filter) {
        if(!filter.value.length || filter.disabled) {
          return filters;
        }

        filters[filter.name] = filter.value;

        return filters;
      }, {});

    var url = location.pathname + '?' + _.map(filters, function(value, key) {
      return queryString({value: value, key: key});
    }).join('');

    table.ajax.url(url);
    table.draw();
  });
});

//# sourceMappingURL=general-filters.js.map
