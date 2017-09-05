$(function() {
  NotificationSystem.init();
});

var NotificationSystem = {
  init: function() {
    var self = this;
    this.$counters = $('.notification-counter');
    this.$container = $('#new_notifications');
    this.$error_import = $('#error_import');
    this.makeNotification = _.template(
        '<button type="button" data-id="<%= id %>" class="btn btn-xs btn-danger btn-flat dismiss-notify pull-right" title="Dispensar"> ' +
        ' <i class="fa fa-times"></i> ' +
        '</button> ' + 
      '<a href="<%= data.link %>" data-id="<%= id %>" class="js-notification" >' +
      '<i class="fa fa-<%= data.icon || "comment" %> text-<%= data.state || "success" %>"></i>' +
      '<%= data.message %>' +
      '</a>'
    );

    $body.on('click', '.js-notification', function(event) {
      var notification = event.currentTarget;
      var ajax = self.markAsRead(notification.dataset.id)

      startLoading();
      ajax.always(stopLoading);

      if(notification.href && notification.href !== '#') {
        event.preventDefault();
        ajax.done(function() {
          location.href = notification.href;
        });
      }
    });
    $body.on('click', '.dismiss-notify', function(event) {
      var notification = event.currentTarget;
      var ajax = self.markAsRead(notification.dataset.id)
      
      $('[data-id="'+notification.dataset.id+'"]').remove();
    });

    this.verifyNotifications();
    setInterval(this.verifyNotifications.bind(this), 3000)
  },
  updateCounters: function(val) {
    return this.$counters.text(val);
  },
  clearContainer: function() {
    this.$container.html('');
  },
  verifyNotifications: function() {
    var ajax = $.get('/notifications');
    ajax.done(this.successCallback.bind(this));
  },
  markAsRead: function(id) {
    return $.post('/notifications/' + id + '/mark-as-read');
  },
  successCallback: function(notifications) {
    var self = this;
    this.updateCounters(notifications.length);
    this.clearContainer();

    notifications.filter(function(notification) {
        return self.parse(notification);
    });


    $('.notifications-menu .menu').slimScroll({
      height: '200px'
    });

  },
  parse: function(notification) {
    this.$container.append(this.makeNotification(notification));
  }
};

