

$(document).ready(function() {
  update.refresh(update.monitor, 10000)
});

var update = {
  refresh: function(func, period) {
    func();
    setInterval(func, period);
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
      $("#uptime").text(response?response.uptime : "N/A");
    }
  }
};

