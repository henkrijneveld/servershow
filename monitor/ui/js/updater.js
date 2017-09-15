

$(document).ready(function() {
  update.refresh(update.monitor, 15000);
  update.refresh(update.operatingsystem, 15000);
  update.refresh(update.hardware, 15000);
  update.refresh(update.services, 5000);
  update.refresh(update.network, 7500);
  update.refresh(update.resources, 2500);
});

var update = {
  refresh: function(func, period) {
    func();
    setInterval(func, period);
  },

  colorservices: function () {
      $("#services").find(".updateok").each(function() {
        var t = $(this).text();
        if (t === "open") {
          $(this).addClass("openport");
        } else {
          $(this).removeClass("openport");
        }
      })
  },

  updatenetwork: function(response) {
    var networkfound = false;
    var net = "<table><tr><td>Interface</td><td>TX</td><td>RX</td></tr>";
    response.networks.forEach(function (network) {
      networkfound = true;
      net += "<tr><td>" + network[0] + ":</td><td>" + parseInt(network[1]).toLocaleString() + "</td><td>" + parseInt(network[2]).toLocaleString() + "</td>";
    });
    net += "</table>";
    if (networkfound) {
      $("#networklist").html(net).addClass("updateok").removeClass("updatenok");
    } else {
      $("#networklist").html("no access").addClass("updatenok").removeClass("updateok");
    }
  },

  updateui: function (response) {
    Object.keys(response).forEach(function (key) {
      $("#" + key).html(response[key]).addClass("updateok").removeClass("updatenok");
    });
  },

  failui: function (id) {
      $("#" + id + " .updateok").addClass("updatenok").removeClass("updateok");
  },

  updateLoadBar: function (name, value) {
    var outer = $("#"+name+"outer");
    var inner = $("#"+name+"inner");

    if (value === "N/A"){
      inner.width("0%");
      outer.css("backgroundColor", "#f4f4f4");
      return;
    }

    var perc = Math.min(100, parseInt(value)/2);

    if (perc <= 40) {
      inner.css("backgroundColor", "darkgreen");
    } else {
      if (perc < 60) {
        inner.css("backgroundColor", "coral");
      } else {
        inner.css("backgroundColor", "darkred");
      }
    }


    inner.width(perc+"%");
  },

 updateresources: function (response) {
    $("#diskinner").width(Math.round(parseFloat(response.rsfreedisk)/parseFloat(response.rstotaldisk) * 100) + "%");
    var memtotal = parseFloat(response.rsmemtotal);
    var memfree = parseFloat(response.rsmemfree);
    if (memtotal > 0) {
      $("#meminner").width(Math.round((memfree / memtotal) * 100) + "%");
    } else {
      $("#meminner").width("0%");
      $("#memouter").css("backgroundColor", "#f4f4f4");
    }

    update.updateLoadBar("avg1", response.rsload1);
    update.updateLoadBar("avg5", response.rsload5);
    update.updateLoadBar("avg15", response.rsload15);

  },

  monitor: function () {
    $.get({
      url: "api/monitor.php",
      success: function(response) {
        update.updateui(response);
      },
      dataType: "json"
    }).fail(function() {
      update.updateui(null);
    });
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
  },

  network: function () {
    $.get({
      url: "api/network.php",
      success: function(response) {
        update.updatenetwork(response);
      },
      dataType: "json"
    }).fail(function() {
      update.failui("network");
    });
  },

  services: function() {
    $.get({
      url: "api/services.php",
      success: function(response) {
        update.updateui(response);
        update.colorservices();
      },
      dataType: "json"
    }).fail(function() {
      update.failui("services");
    });
  },

  resources: function() {
    $.get({
      url: "api/resources.php",
      success: function(response) {
        update.updateui(response);
        update.updateresources(response);
      },
      dataType: "json"
    }).fail(function() {
      update.failui("resources");
    });
  }

};

