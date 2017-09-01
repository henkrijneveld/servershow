<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 21-8-17
 * Time: 15:25
 */
include_once "../lib/common.php";
CommonFunctions::validateSession();

$l = new Linux();

$response = new stdClass();
$response->computername = $l->getHostName();
$response->uptime = $l->getUptime();

echo json_encode($response);

