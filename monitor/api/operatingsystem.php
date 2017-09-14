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
$response->osuptime = CommonFunctions::makereadableseconds($l->getUptime());

$l->getUptime();
$response->osrelease = $l->getRelease();
$response->os = $l->getOS();
$response->osversion = $l->getVersion();
$response->osmachinetype = $l->getMachineType();

$useinfo = $l->getUserGroup();
$response->osusergroup = $useinfo[0]." (".$useinfo[1].") - ".$useinfo[2]." (".$useinfo[3].")";
if ($useinfo[4]) $response->osusergroup .= "<br/>Groupmembers: ".implode(" ", $useinfo[4]);

$useinfo = $l->getUserGroupThisFile();
$response->osfileusergroup = $useinfo[0]." (".$useinfo[1].") - ".$useinfo[2]." (".$useinfo[3].")";
if ($useinfo[4]) $response->osfileusergroup .= "<br/>Groupmembers: ".implode(" ", $useinfo[4]);

echo json_encode($response);

