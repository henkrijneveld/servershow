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

$response->svwebserver = $l->getPortOpen(80) ? "open" : "closed";
$response->svftp = $l->getPortOpen(21) ? "open" : "closed";
$response->svssh = $l->getPortOpen(22) ? "open" : "closed";
$response->svtelnet = $l->getPortOpen(22) ? "open" : "closed";
$response->svsendmailplain = $l->getPortOpen(25) ? "open" : "closed";
$response->svreceivemailplain = $l->getPortOpen(110) ? "open" : "closed";
$response->svhttps = $l->getPortOpen(443) ? "open" : "closed";
$response->svsmtpsec = $l->getPortOpen(587) ? "open" : "closed";
$response->svimapsec = $l->getPortOpen(993) ? "open" : "closed";
$response->svpopsec = $l->getPortOpen(995) ? "open" : "closed";
$response->svmailcatcher = $l->getPortOpen(1080) ? "open" : "closed";
$response->svmysql = $l->getPortOpen(3306) ? "open" : "closed";


echo json_encode($response);

