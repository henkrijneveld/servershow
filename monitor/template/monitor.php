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
?>


