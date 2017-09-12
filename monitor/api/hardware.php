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

$cores = $l->getCores();
if ($cores) {
  $response->hwcpucores = $l->getCores();
} else {
  $response->hwcpucores = "N/A";
}


$cpu = $l->getCPU();
if ($cpu) {
  $response->hwcputype = isset($cpu["cputype"])?$cpu["cputype"]:"N/A";
  $response->hwcpufreq = isset($cpu["cpufreq"])?$cpu["cpufreq"]:"N/A";
  $response->hwcpucache = isset($cpu["cpucache"])?$cpu["cpucache"]:"N/A";
  $response->hwmachinetype = $cpu["machinetype"];
}

$mem = $l->getMem();
$response->hwmemtotal = isset($mem["memtotal"])?($mem["memtotal"]/1000)." MB":"N/A";

echo json_encode($response);

