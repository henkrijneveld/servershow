<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 1-9-17
 * Time: 15:09
 */
?>
<?php $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$ip = getenv('HTTP_CLIENT_IP')?:
  getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?:
      getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
          getenv('REMOTE_ADDR');
?>
<h1><span class="normalfont">Monitoring</span> <span id="computername"></span><span class="normalfont"> for </span>
  <?php echo $url; ?>
<?php   echo " (".$_SERVER['SERVER_ADDR'].")"; ?>
</h1>
<?php
if ($ip) {
  echo "<p>Requested from client {$ip}</p>";
}

$basedir = ini_get('open_basedir');
if ($basedir != "") {
  echo "<div style='padding-left: 10px; border-left: red solid 5px;'><p>Some data possibly unavailable due to open_basedir restrictions<br/>";
  echo "Current setting open_basedir = ".$basedir."</p></div>";
}

$shellexec = ini_get('disable_functions');
if (strpos($shellexec, "shell_exec") === false) {
  echo "<div style='padding-left: 10px; border-left: green solid 5px;'><p>shell_exec is not disabled!<br/>Detailed information can be obtained.</div>";
}

?>


