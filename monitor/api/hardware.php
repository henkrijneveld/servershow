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
$cpu = $l->getCPU();
if ($cpu) {
  $response->hwcpucores = isset($cpu["cpucores"])?$cpu["cpucores"]:"N/A";
  $response->hwcputype = isset($cpu["cputype"])?$cpu["cputype"]:"N/A";
  $response->hwcpufreq = isset($cpu["cpufreq"])?$cpu["cpufreq"]:"N/A";
  $response->hwcpucache = isset($cpu["cpucache"])?$cpu["cpucache"]:"N/A";
  $response->hwmachinetype = $cpu["machinetype"];
}

$mem = $l->getMem();
if($mem) {
  $response->hwmemtotal = isset($mem["memtotal"])?($mem["memtotal"]/1000)." MB":"N/A";
  $response->hwmemfree = isset($mem["memfree"])?($mem["memfree"]/1000)." MB":"N/A";
}

echo json_encode($response);

