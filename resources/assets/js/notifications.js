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
    if($('#notificacoesLidas').attr('class') == 'dropdown notifications-menu') {
      var ajax = $.get('/notifications');
      ajax.done(this.successCallback.bind(this));
    }
  },
  markAsRead: function(id) {
    return $.post('/notifications/' + id + '/mark-as-read');
  },
  successCallback: function(notifications) {
    var self = this;
    this.updateCounters(notifications.length);
    this.clearContainer();

    notifications.filter(function(notification) {
      if(notification.type !== 'App\\Notifications\\PlanilhaProcessamento') {
        return self.parse(notification);
      }

      return self.parseProcessamento(notification);
    });


    $('.notifications-menu .menu').slimScroll({
      height: '200px'
    });

  },
  parse: function(notification) {
    this.$container.append(this.makeNotification(notification));
  },
  parseProcessamento: function(notification) {
    if (notification.data.success) {
      return this.$container.append('<a href="#" data-toggle="modal" data-target="#myModalsuccess" class="js-notification" data-id="' + notification.id + '"><i class="fa fa-check text-green"></i> Importação com sucesso</a>');
    }

    if (notification.data.error && notification.data.error.length) {
      var self = this;
      notification.data.error.map(function(val) {
        self.$error_import.append('<tr><td>' + val + '</td></tr>');
      });
    }

    this.$container.append('<a href="#" data-toggle="modal" data-target="#myModalerror" class="js-notification" data-id="' + notification.id + '"><i class="fa fa-warning text-yellow"></i> Erro na importação</a>');
  }
};

function notificacoesLidas() {
    $.ajax('/notifications/notificacoesLidas');
}
