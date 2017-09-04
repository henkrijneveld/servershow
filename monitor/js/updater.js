

$(document).ready(function() {
  update.refresh(update.monitor, 10000);
  update.refresh(update.operatingsystem, 5000);
  update.refresh(update.hardware, 5000);
});

var update = {
  refresh: function(func, period) {
    func();
    setInterval(func, period);
  },

  updateui: function (response) {
    Object.keys(response).forEach(function (key) {
      $("#" + key).html(response[key]).addClass("updateok").removeClass("updatenok");
    });
  },

  failui: function (id) {
      $("#" + id + " .updateok").addClass("updatenok").removeClass("updateok");
  },

  monitor: function () {
    $.get({
      url: "api/monitor.php",
      success: function(response) {
        update_ui(response);
      },
      dataType: "json"
    }).fail(function() {
      update_ui(null);
    });

    function update_ui(response) {
      $("#computername").text(response?response.computername : "N/A");
    }
  },

  operatingsystem: function () {
    $.get({
      url: "api/operatingsystem.php",
      success: function(response) {
        update.updateui(response);
      },
      dataType: "json"
    }).fail(function() {
      update.failui("operatingsystem");
    });
  },

  hardware: function () {
    $.get({
      url: "api/hardware.php",
      success: function(response) {
        update.updateui(response);
      },
      dataType: "json"
    }).fail(function() {
      update.failui("hardware");
    });
  }

};

