(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.site_notifications_behavior = {
    attach: function (context, settings) {
      if (context === document) {
        site_notifications_loaded = true;
        var notificationhtml = [];
        var site_notifications = drupalSettings.site_notifications['site-notification'];
        for (var position in site_notifications) {
          for (var key = 0; key < site_notifications[position].length; key ++) {
            if (notificationhtml[position]){
              notificationhtml[position] += '<p>' + site_notifications[position][key]['message'] + '</p>';
            } else {
              var positionStyle = 'min-width:150px;max-width:350px;padding:15px;';
              var vertPosition;
              switch(position){
                case 'topleft':
                  vertPosition = 'top:0;';
                  positionStyle += vertPosition + 'left:0;';
                  break;

                case 'topcenter':
                  vertPosition = 'top:0;';
                  positionStyle += vertPosition + 'left:50%;transform:translateX(-50%);';
                  break;

                case 'topright':
                  vertPosition = 'top:0;';
                  positionStyle += vertPosition + 'right:0;';
                  break;

                case 'leftcenter':
                  vertPosition = 'top:50%;';
                  positionStyle += vertPosition + 'left:0;transform:translateY(-50%);';
                  break;

                case 'center':
                  vertPosition = 'top:50%;';
                  positionStyle += vertPosition + 'left:50%;transform: translate(-50%,-50%);';
                  break;

                case 'rightcenter':
                  vertPosition = 'top:50%;';
                  positionStyle += vertPosition + 'right:0;transform:translateY(-50%);';
                  break;

                case 'bottomleft':
                  vertPosition = 'bottom:0;';
                  positionStyle += vertPosition + 'left:0;';
                  break;

                case 'bottomcenter':
                  vertPosition = 'bottom:0;';
                  positionStyle += vertPosition + 'left:50%;transform: translateX(-50%);';
                  break;

                case 'bottomright':
                  vertPosition = 'bottom:0;';
                  positionStyle += vertPosition + 'right:0;';
                  break;
              }
              var fill = '';
              if(site_notifications[position][key]['fill'] == 1){
                positionStyle = vertPosition + 'left:0%;width:100%;max-width:100%;padding:5px;';
              }
              notificationhtml[position] =
              '<div style="position:relative;">' +
                '<div class ="notification" style="position:fixed;' + positionStyle + 'background-color:pink;z-index:9999;" role="alert" data-notification-id=' + site_notifications[position][key]['custom_revision_id'] + ' style="max-width:100%;">' +
                  '<div style="min-height:25px;border-bottom: 1px solid black;margin-right: 15px;>' +
                    '<strong style="font-size:14px;margin-right:auto">Notification</strong>' +
                    '<button style="float:right;font-size:11px;" type="button" data-dismiss="site-notification" aria-label="Close">X</button>' +
                  '</div>' +
                  '<div style="margin-right:15px">' +
                    '<p>' + site_notifications[position][key]['message'] + '</p>';
            }
            if( key + 1 == site_notifications[position].length){
              notificationhtml[position] += '</div></div></div>';
            }
          }
        }
        for (var key in notificationhtml) {
          $('body').append(notificationhtml[key]);
        }

         // Get all potential notifications.
          var notificationElList = [].slice.call(document.querySelectorAll('.notification'));
          // Loop through notifications and find/set configs.
          var notificationList = notificationElList.map(function (notificationEl) {
            var notification = notificationEl.dataset.notificationId;
            if($.cookie(notification) == undefined){
              $.cookie(notification, 'show', { domain: document.domain });
            } else if ($.cookie(notification) == 'hide') {
              $('[data-notification-id="' + notificationEl.dataset.notificationId + '"]').hide();
            }
          })
        // Event for dismissing notification and storing status.
        // If hidden we want to set storage value.
        $('[data-dismiss="site-notification"]').on('click', function () {
          var notificationEl = $(this).closest('.notification');
          var notification_id = $(this).closest('.notification').attr('data-notification-id');
          notificationEl.hide();
          $.cookie(notification_id, 'hide', { domain: document.domain });
        });

      }

    }
  }

})(jQuery, Drupal, drupalSettings);
