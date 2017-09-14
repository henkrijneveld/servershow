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
$disks = $l->getDiskInfo(__FILE__);
if ($disks) {
  $response->rstotaldisk = floor($disks[0]/10000000)/100 . " GB";
  $response->rsfreedisk = floor($disks[1]/10000000)/100 . " GB";
}

$mem = $l->getMem();
if($mem) {
  $response->rsmemtotal = $mem[0] != Linux::NA ?($mem[0]/1000)." MB":Linux::NA;
  $response->rsmemfree = $mem[1] != Linux::NA ?($mem[1]/1000)." MB":Linux::NA;
}

$load = $l->getLoad();
if ($load) {
  $response->rsload1 = $load[0] === false ? Linux::NA : min(600, floor($load[0] * 100)) . "%";
  $response->rsload5 = $load[1] === false ? Linux::NA : min(600, floor($load[1] * 100)) . "%";
  $response->rsload15 = $load[2] === false ? Linux::NA : min(600, floor($load[2] * 100)) . "%";
}

echo json_encode($response);

