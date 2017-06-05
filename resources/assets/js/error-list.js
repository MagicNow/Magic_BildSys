window.ErrorList = (function() {
  function ErrorList(errors) {
    this.errors = errors;
    this.parse = _.flow([_.values, _.flatten]);
  }

  ErrorList.prototype.toHTML = function() {

    var errors = this.parse(this.errors)
      .map(this.makeItem);

    return _(['<ul class="list-group">', errors, '</ul>']).flatten().join('');
  }

  ErrorList.prototype.makeItem = function(value) {
    var template = _.template(
      '<li class="list-group-item"><%= value %></li>'
    );

    return template({
      value: value
    });
  };

  return ErrorList;
}());

