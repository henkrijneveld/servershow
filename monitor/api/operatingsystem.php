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
$response->osuptime = $l->getUptime();
$response->osrelease = $l->getRelease();
$response->os = $l->getOS();
$response->osversion = $l->getVersion();
$response->osmachinetype = $l->getMachineType();
$useinfo = $l->getUserGroup();
$response->osusergroup = $useinfo[0]." - ".$useinfo[1];
if ($useinfo[2]) {
  $response->osusergroup .= "<br/>Groupmembers: ".$useinfo[2];
}

echo json_encode($response);

