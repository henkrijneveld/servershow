<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 20-8-17
 * Time: 20:22
 */

class Linux
{
  const NA = 'N/A';

  public function getHostName()
  {
    return (php_uname('n'));
  }

  public function getUptime()
  {
    $s = shell_exec('cat /proc/uptime');
    if (!$s) {
      $s = @file_get_contents("/proc/uptime");
    }
    if ($s) {
      $s = CommonFunctions::makereadableseconds($s);
    } else {
      $s = self::NA;
    }

    return ($s);
  }
}


