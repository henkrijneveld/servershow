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
  $response->rsmemtotal = isset($mem["memtotal"])?($mem["memtotal"]/1000)." MB":"N/A";
  $response->rsmemfree = isset($mem["memfree"])?($mem["memfree"]/1000)." MB":"N/A";
}

$load = $l->getLoad();
if ($load) {
  $response->rsload1 = $load[0] . "%";
  $response->rsload5 = $load[1] . "%";
  $response->rsload15 = $load[2] . "%";
}

echo json_encode($response);

