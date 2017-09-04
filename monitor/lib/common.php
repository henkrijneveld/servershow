<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 21-8-17
 * Time: 15:09
 */

spl_autoload_register(function($class) {
    include_once(__DIR__."/../core/".strtolower($class).".php");
});

class CommonFunctions
{
    static function makereadableseconds($s) {
        $s = (int)strtok($s, ".");
        $part_seconds = str_pad($s % 60, 2, "0", STR_PAD_LEFT);
        $s = intdiv($s, 60);
        $part_minutes = str_pad($s % 60, 2, "0", STR_PAD_LEFT);
        $s = intdiv($s, 60);
        $part_hours = str_pad($s % 24, 2, "0", STR_PAD_LEFT);
        $s = intdiv($s, 24);
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

    static function authoriseSession() {
        if (session_id() === "") session_start();
        $_SESSION['monitoraccess'] = 1;
    }

    static function validateSession() {
        if (session_id() === "") session_start();
        if (!isset($_SESSION['monitoraccess'])) die ("{}");
    }
}
