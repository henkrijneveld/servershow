<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 21-8-17
 * Time: 15:09
 */

spl_autoload_register(function ($class) {
  include_once(__DIR__ . "/../core/" . strtolower($class) . ".php");
});

class CommonFunctions
{
  static function makereadableseconds($s)
  {
    if (!is_int($s)) return $s;

    function partition($value, $divisor) {
      return str_pad($value % $divisor , 2, "0", STR_PAD_LEFT);
    }

    $part_seconds = partition($s, 60);
    $s = floor($s / 60);
    $part_minutes = partition($s, 60);
    $s = floor($s / 60);
    $part_hours = partition($s, 24);;
    $s = floor($s / 24);
    $part_days = $s;

    $s = "";
    if (intval($part_days)) {
      $s = $part_days . "d ";
    }
    if (intval($part_hours) || $s) {
      $s .= $part_hours . "h ";
    }
    if (intval($part_minutes) || $s) {
      $s .= $part_minutes . "m ";
    }
    $s .= $part_seconds . "s";

    return $s;
  }

  static function authoriseSession()
  {
    if (session_id() === "") session_start();
    $_SESSION['monitoraccess'] = 1;
  }

  static function validateSession()
  {
    if (session_id() === "") session_start();
    if (!isset($_SESSION['monitoraccess'])) die ("{}");
  }

  static function setNameValue($name, $value)
  {
    if (isset($_SESSION)) {
      $_SESSION[$name] = $value;
    }
    return $value;
  }

  static function getNameValue($name)
  {
    if (isset($_SESSION) && isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return null;
  }
}
