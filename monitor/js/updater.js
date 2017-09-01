

$(document).ready(function() {
  update.server();
  setInterval(update.server, 2500);
});

var update = {
  server: function () {
    $.get({
      url: "api/system.php",
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

